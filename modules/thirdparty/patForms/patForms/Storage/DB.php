<?php
/**
 * patForms storage DB
 *
 * $Id: DB.php,v 1.1 2006/04/03 20:41:08 eric Exp $
 *
 * @package		patForms
 * @subpackage	Storage
 * @author		Stephan Schmidt <schst@php-tools.net>
 */

/**
 * needs PEAR::DB
 */
require_once 'DB.php';
 
/**
 * patForms storage DB
 *
 * Stores form data in a database.
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Storage
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Storage_DB extends patForms_Storage
{
   /**
	* datasource name
	*
	* @access	private
	* @var		string
	*/
	var $_dsn;

   /**
	* table name
	*
	* @access	private
	* @var		string
	*/
	var $_table;

   /**
	* instance of PEAR::DB
	*
	* @access	private
	* @var		object
	*/
	var $_db;

   /**
	* field map
	*
	* @access	private
	* @var		string
	*/
	var $_fieldMap	=	array();

   /**
	* set the dsn and table
	*
	* @access	public
	* @param	string		datasource name
	* @param	string		table
	*/
	function setStorageLocation( $dsn, $table )
	{
		$this->_dsn		=	$dsn;
		$this->_table	=	$table;
	}

   /**
	* set the field map
	*
	* The field map is an associative array, that defines how
	* the form elements (key) map to fields in the
	* table (value)
	*
	* @access	public
	* @param	array		field map
	*/
	function setFieldMap($fieldMap)
	{
		$this->_fieldMap = $fieldMap;
	}

   /**
	* get an entry
	*
	* This tries to find an entry in the storage container
	* that matches the current data that has been set in the
	* form and populates the form with the data of this
	* entry
	*
	* @access	public
	* @param	object patForms		patForms object that should be stored
	* @return	boolean				true on success
	*/
	function loadEntry(&$form)
	{
		$values  = $form->getValues();
		$primary = $this->getPrimary($values);

		// no primary key, storage will only add entries
		if (empty($primary)) {
			return array();
		}
		
		/**
		 * entry does not exists
		 */
		if (!$data = $this->_entryExists($primary)) {
			return array();
		}
			
		$values = $this->_unmapFields($data);

		$form->setValues($values);
		return true;
	}

   /**
	* adds an entry to the storage
	*
	* The entry will be appended at the end of the file.
	*
	* @abstract
	* @param	object patForms		patForms object that should be stored
	* @return	boolean				true on success
	*/
	function _addEntry(&$form)
	{
		$values = $form->getValues();
		
		$result = $this->_prepareConnection();
		if (PEAR::isError($result)) {
			return patErrorManager::raiseError(PATFORMS_STORAGE_ERROR_STORAGE_INVALID, 'Could not connect to database: ' . $result->getMessage());
		}
		$values	= $this->_mapFields($values);
		
		$values = array_merge($values, $this->_staticValues);
		
		$fields = array_keys($values);
		$values = array_map(array($this->_db, 'quoteSmart'), array_values($values));

		$query  = sprintf('INSERT INTO %s (%s) VALUES (%s);', 
		                  $this->_table,
		                  implode(',', $fields),
		                  implode(',', $values)
		              );
		
		$result = $this->_db->query($query);
		
		if (PEAR::isError($result)) {
			return patErrorManager::raiseError(PATFORMS_STORAGE_ERROR_STORAGE_INVALID, 'Query failed: ' . $result->getMessage());
		}
		return true;		
	}

   /**
	* updates an entry in the storage
	*
	* Implement this in the concrete storage container.
	*
	* @abstract
	* @param	object patForms		patForms object that should be stored
	* @return	boolean				true on success
	*/
	function _updateEntry( &$form, $primary )
	{
		$values = $form->getValues();
		
		$result = $this->_prepareConnection();
		if (PEAR::isError($result)) {
			return patErrorManager::raiseError(PATFORMS_STORAGE_ERROR_STORAGE_INVALID, 'Could not connect to database: ' . $result->getMessage());
		}
		$values	 = $this->_mapFields($values);
		$primary = $this->_mapFields($primary);
		
		$tmp = array();
		foreach ($values as $key => $value) {
			array_push( $tmp, $key.'='.$this->_db->quoteSmart( $value ) );
		}

		$ptmp = array();
		foreach ($primary as $key => $value) {
			array_push( $ptmp, $key.'='.$this->_db->quoteSmart( $value ) );
		}

		$query  = 'UPDATE '.$this->_table.' SET '.implode( ', ', $tmp ).' WHERE '.implode( ' AND ', $ptmp );
		$result = $this->_db->query($query);
		if (PEAR::isError($result)) {
			return patErrorManager::raiseError(PATFORMS_STORAGE_ERROR_STORAGE_INVALID, 'Query failed: ' . $result->getMessage());
		}
		return true;		
	}

   /**
	* check, whether an entry exists
	*
	* @access	private
	* @param	array
	*/
	function _entryExists($primary)
	{
		$result = $this->_prepareConnection();
		if (PEAR::isError($result)) {
			return patErrorManager::raiseError(PATFORMS_STORAGE_ERROR_STORAGE_INVALID, 'Could not connect to database: ' . $result->getMessage());
		}
		$primary = $this->_mapFields($primary);

		$tmp = array();
		foreach ($primary as $key => $value) {
			array_push($tmp, $key.'='.$this->_db->quoteSmart($value));
		}

		$query	= 'SELECT * FROM '.$this->_table.' WHERE '.implode( ' AND ', $tmp );
		$result	= $this->_db->getRow( $query, array(), DB_FETCHMODE_ASSOC );
		if (PEAR::isError($result)) {
			return patErrorManager::raiseError(PATFORMS_STORAGE_ERROR_STORAGE_INVALID, 'Could not connect to database: ' . $result->getMessage());
		}

		if (empty($result)) {
			return false;
		}

		return $result;
	}

   /**
	* map the values to the correct fields
	*
	* @access	private
	* @param	array		values
	* @return	array		values mapped to the correct fields
	*/
	function _mapFields( $values )
	{
		if (empty($this->_fieldMap)) {
			return $values;
		}

		$fields	=	array();
		foreach ($this->_fieldMap as $el => $field) {
			if (!isset($values[$el])) {
				continue;
			}

			$fields[$field] = $values[$el];
		}
		return $fields;	
	}

   /**
	* map the fields to the correct elements
	*
	* @access	private
	* @param	array		values
	* @return	array		values mapped to the correct fields
	*/
	function _unmapFields( $values )
	{
		if (empty($this->_fieldMap)) {
			return $values;
		}

		$fields	= array();
		foreach ($this->_fieldMap as $el => $field) {
			if( !isset($values[$field])) {
				continue;
			}

			$fields[$el] = $values[$field];
		}
		return $fields;	
	}

   /**
	* prepare the DB connection
	*
	* @access	private
	*/
	function _prepareConnection()
	{
		if ($this->_db != null) {
			return true;
		}
		
		if (is_object($this->_dsn)) {
			$this->_db = &$this->_dsn;
			return true;
		}

		$this->_db = &DB::connect($this->_dsn);
		if (PEAR::isError($this->_db)) {
			$error = $this->_db;
			$this->_db = null;
			return $error;
		}
		return true;
	}
}
?>