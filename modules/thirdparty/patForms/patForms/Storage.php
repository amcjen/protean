<?php
/**
 * patForms storage base class - extend this to create your own storage containers.
 *
 * $Id: Storage.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Storage
 */

/**
 * could not open storage container
 */
define('PATFORMS_STORAGE_ERROR_STORAGE_INVALID', 'patForms::Storage::0001');

/**
 * patForms storage base class - extend this to create your own storage containers.
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Storage
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Storage
{
   /**
	* fields that contains the primary key(s) for the data
	*
	* Defaults to 'id'
	*
	* @access	private
	* @var		array
	*/
	var $_primaryFields	= array('id');

   /**
	* static values, that will be added to all entries
	*
	* @access	private
	* @var		array
	*/
	var $_staticValues = array();

   /**
	* set the primary key(s) for the storage container
	*
	* You may either pass a string or an array containing strings
	* if your storage container has a composite primary key
	*
	* @access	public
	* @param	mixed	primary key field
	*/
	function setPrimaryField( $field )
	{
		if (!is_array($field)) {
			$field = array($field);
		}

		$this->_primaryFields = $field;		
	}

   /**
	* get the primary key for an entry
	*
	* This will return an associative array with all fields
	* that form the primary key of the entry.
	*
	* If the form does not contain all values for the
	* primary key, an empty array will be returned.
	*
	* @access	protected
	* @param	array	form values
	* @return	array	primray values
	*/
	function getPrimary( $values )
	{
		$primary = array();
		foreach ($this->_primaryFields as $p) {
			if (isset($values[$p])) {
				$primary[$p] = $values[$p];
            } else {
				return array();
			}
		}
		return $primary;
	}

   /**
	* set static values, that will be added to all entries
	*
	* @access	public
	* @param	array
	*/
	function setStaticValues($staticValues)
	{
	    $this->_staticValues = $staticValues;
	}
	
   /**
	* store an entry
	*
	* This method decides whether a new entry has to be
	* created or an old entry has to be updated based
	* on the values of the form data
	*
	* If the elements that store the primary key are empty,
	* a new entry will be added, otherwise the entry will be updated
	*
	* @access	public
	* @param	object patForms		patForms object that should be stored
	* @return	boolean				true on success
	*/
	function storeEntry( &$form )
	{
		$values  = $form->getValues();
		$primary = $this->getPrimary($values);
		$new	 = false;
		if (empty($primary)) {
			$new = true;
		}

		if(!$new) {
		    $exists = $this->_entryExists($primary);
		    if (patErrorManager::isError($exists)) {
		    	return $exists;
		    }
			if(!$exists) {
				$new = true;
			}
		}
		
		if ($new) {
			$result = $this->_addEntry($form);
		} else {
			$result = $this->_updateEntry($form, $primary);
		}
		return $result;
	}

   /**
    * validate the form
    *
    * @access   public
	* @param	object  $form  The form
	* @return   True or false, the validation result
    */
	function validateEntry(&$form)
	{
	    return true;
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
	function loadEntry( &$form )
	{
	}
	
   /**
	* check, whether an entry exists
	*
	* @author   Frank Kleine <frank.kleine@schlund.de>
	* @access	public
	* @param	object patForms		patForms object that should be stored
	* @return	boolean             true if object exists
	*/
	function entryExists(&$form)
	{
	    $values  = $form->getValues();
		$primary = $this->getPrimary($values);
	    $data    = $this->_entryExists($primary);
	    if ($data == false) {
	        return false;
	    }
	    return true;
	}

   /**
	* adds an entry to the storage
	*
	* Implement this in the concrete storage container.
	*
	* @abstract
	* @param	object patForms		patForms object that should be stored
	* @return	boolean				true on success
	*/
	function _addEntry( &$form )
	{
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
	function _updateEntry( &$form )
	{
	}

   /**
	* checks, whether an entry exists
	*
	* Implement this in the concrete storage container.
	*
	* @abstract
	* @param	array			primary values
	* @return	boolean|array	values of the entry, if it exists, false otherwise	
	*/
	function _entryExists( $primary )
	{
	    return false;
	}
}
?>