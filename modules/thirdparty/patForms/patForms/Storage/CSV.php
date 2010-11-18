<?php
/**
 * patForms storage CSV
 *
 * $Id: CSV.php,v 1.1 2006/04/03 20:41:08 eric Exp $
 *
 * @package		patForms
 * @subpackage	Storage
 * @author		Stephan Schmidt <schst@php-tools.net>
 */
 
/**
 * patForms storage CSV
 *
 * Stores form data in a CSV file.
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Storage
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Storage_CSV extends patForms_Storage
{
   /**
	* storage file
	*
	* @access	private
	* @var		string
	*/
	var $_file;

   /**
	* csv delimeter
	*
	* @access	private
	* @var		string
	*/
	var $_delimeter	= ';';

   /**
	* linefeed
	*
	* @access	private
	* @var		string
	*/
	var $_linefeed = "\n";

   /**
	* field order
	*
	* @access	private
	* @var		string
	*/
	var $_fieldOrder = array();

   /**
	* set the storage file
	*
	* @access	public
	* @param	string		filename
	*/
	function setFile($file)
	{
		$this->_file = $file;
		if (file_exists($file) && !is_writable($file)) {
			return patErrorManager::raiseError(PATFORMS_STORAGE_ERROR_STORAGE_INVALID, 'Could not open ' . $file . ' for writing.');
		}
	}

   /**
	* set the delimeter
	*
	* @access	public
	* @param	string		delimeter
	*/
	function setDelimeter($delimeter)
	{
		$this->_delimeter = $delimeter;
	}

   /**
	* set the field order
	*
	* @access	public
	* @param	array		field order
	*/
	function setFieldOrder($fields)
	{
		$this->_fieldOrder = $fields;
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
		$primary = $this->getPrimary( $values );

		/**
		 * primary key not given
		 */
		if (empty($primary)) {
			return array();
		}

		/**
		 * entry does not exists
		 */
		if (!$data = $this->_entryExists($primary)) {
			return array();
		}

		$values = array();
		foreach ($this->_fieldOrder as $pos => $field) {
			if (isset($data[$pos])) {
				$values[$field] = $data[$pos];
			}
		}
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
	function _addEntry( &$form )
	{
		$values = $form->getValues();
		
		$line	= array();
		foreach ($this->_fieldOrder as $field) {
			if (!isset( $values[$field])) {
				$value = '';
			} else {
				$value = $values[$field];
			}			
			array_push($line, '"'.addslashes( $value ).'"');
		}
		$line = implode( $this->_delimeter, $line ) . $this->_linefeed;
		$fp   = @fopen($this->_file, 'a');
		if (!$fp) {
			return patErrorManager::raiseError(PATFORMS_STORAGE_ERROR_STORAGE_INVALID, 'Could not open the supplied csv file.');
		}
		
		flock($fp, LOCK_EX);
		fwrite($fp, $line);
		flock($fp, LOCK_UN);
		fclose($fp);
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
	function _updateEntry(&$form, $primary)
	{
		$keys = array();
		foreach ($primary as $key => $value) {
			$pos = array_search($key, $this->_fieldOrder);
			$keys[$pos] = $value;
		}
	
		$fp = @fopen($this->_file, 'r');
		if (!$fp) {
			return patErrorManager::raiseError( PATFORMS_STORAGE_ERROR_STORAGE_INVALID, 'Could not open the supplied csv file.' );
		}
		
		$new = array();
		flock($fp, LOCK_SH);
		while (!feof($fp)) {
			$tmp = fgetcsv($fp, 10000, $this->_delimeter);

			foreach ($keys as $key => $value) {
				if ($tmp[$key] != $value) {
					array_push($new, $tmp);
					continue;
				}
				
				$line = array();
				$values = $form->getValues();
				foreach ($this->_fieldOrder as $field) {
					if (!isset( $values[$field])) {
						$value = '';
					} else {
						$value = $values[$field];
					}
					array_push($line, $value);
				}
				array_push($new, $line);
			}
		}
		flock($fp, LOCK_UN);
		fclose($fp);

		/**
		 * rewrite the file
		 */
		$fp = @fopen($this->_file, 'w');
		if(!$fp) {
			return patErrorManager::raiseError( PATFORMS_STORAGE_ERROR_STORAGE_INVALID, 'Could not open the supplied csv file.' );
		}
		
		flock($fp, LOCK_EX);

		foreach ($new as $line) {
			if (empty($line)) {
				continue;
			}
			for ($i = 0; $i < count( $line ); $i++) {
				$line[$i] = '"'.addslashes($line[$i]).'"';
			}
			$line = implode($this->_delimeter, $line) . $this->_linefeed;
			fwrite($fp, $line);
		}

		flock($fp, LOCK_UN);
		fclose($fp);
		return true;
	}

   /**
	* check, whether an entry exists
	*
	* @access	private
	* @param	array
	*/
	function _entryExists( $primary )
	{
		$keys = array();
		foreach ($primary as $key => $value) {
			$pos = array_search($key, $this->_fieldOrder);
			$keys[$pos] = $value;
		}
	
		$fp = @fopen($this->_file, 'r');
		if (!$fp) {
			return false;
		}
		flock($fp, LOCK_SH);
		while(!feof($fp)) {
			$tmp = fgetcsv( $fp, 10000, $this->_delimeter );
			foreach ($keys as $key => $value) {
				if ($tmp[$key] != $value) {
					continue;
				}

				flock($fp, LOCK_UN);
				fclose($fp);
				return $tmp;
			}
		}
		flock($fp, LOCK_UN);
		fclose($fp);
		return false;
	}
}
?>