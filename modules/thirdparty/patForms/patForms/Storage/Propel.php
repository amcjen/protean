<?php
/**
 * Storage for patForms integration with Propel
 *
 * This class implements the patForms_Storage interface to integrate with
 * a Propel object peer.
 *
 * Please note: this class is currently php5 only since its solely used
 * as a base class for patForms_Definitions_Propel in order to integrate
 * patForms with Propel5 as a "rapid form solution".
 *
 * $Id: Propel.php,v 1.1 2006/04/03 20:41:08 eric Exp $
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Storage
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 * @see         patForms_Definition
 */

/**
 * Storage for patForms integration with Propel
 *
 * This class implements the patForms_Storage interface to integrate with
 * a Propel object peer.
 *
 * Please note: this class is currently php5 only since its solely used
 * as a base class for patForms_Definitions_Propel in order to integrate
 * patForms with Propel5 as a "rapid form solution".
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Storage
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 * @see         patForms_Definition
 */
class patForms_Storage_Propel extends patForms_Storage
{
   /**
	* Stores the classname of the Propel object
	*
	* @var      object
	* @access	private
	*/
	private $classname;

   /**
	* Stores the Propel peer instance
	*
	* @var      object
	* @access	private
	*/
	private $peer;

   /**
	* Inits the storage from a Propel peer
	*
	* Sets the name of the Propel object classname, creates the Propel peer and
	* sets the primary field to 'Id'.
	*
	* @access	public
	* @param	string  $peername  The classname of the Propel peer
	*/
	public function setStorageLocation($peername)
	{
		$this->peer = new $peername();
		$this->classname = array_pop($tmp = explode('.', $this->peer->getOMClass()));

		$primary = array();
		$object = new $this->classname();
		foreach($object->buildPkeyCriteria()->keys() as $key) {
			$primary[] = BasePeer::translateFieldName(
				$this->classname, $key, 'colName', 'phpName');
		}
		$this->setPrimaryField($primary);
	}

   /**
	* Returns a Creole Criteria object for the object lookup
	*
	* @access	public
	* @param	array  $values  The values to create the Criteria
	* @return   The Criteria object
	*/
	private function getCriteria($values)
	{
		$object = new $this->classname();
		$primary = $this->getPrimary($values);
		$object->fromArray($primary);
		return $object->buildPkeyCriteria();
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
	public function loadEntry(&$form)
	{
		if(!$object = $this->_entryExists($form->getValues())) {
			// entry does not exists (why return an array here??)
			return array();
		}

		$form->setValues($object->toArray());
		return true;
	}

   /**
	* Lets the Propel peer validate the form data.
	*
	* This method gets called by the patForms instance by being registered
	* to its onValidate event. It should not be necessary to call it this
	* method otherwise.
	*
	* @access	public
	* @param	object  $form  The form
	* @return   True or false, the validation result
	*/
	public function validateEntry(&$form)
	{
		if (!$object = $this->_entryExists($form->getValues())) {
			$object = new $this->classname();
		}
		$object->fromArray($form->getValues());
		$result = $object->validate();

		if ($result !== true) {
			$peer = $object->getPeer();
			foreach($result as $colname => $error) {
				$name = $peer->translateFieldname($colname, 'colName', 'phpName');
				$element = $form->getElement($name);
				$element->addValidatorErrorCodes(array(
					'C' => array(
						1 => $error->getMessage(),
					),
				), 1000);
				$element->addValidationError(1001);
			}
			return false;
		}
		return true;
	}

   /**
	* adds an entry to the storage
	*
	* @param	object patForms		patForms object that should be stored
	* @return	boolean				true on success
	*/
	public function _addEntry(&$form)
	{
		$object = new $this->classname();
		$object->fromArray($form->getValues());
		$object->save();
		return true;
	}

   /**
	* updates an entry in the storage
	*
	* @param	object patForms		patForms object that should be stored
	* @return	boolean				true on success
	*/
	public function _updateEntry(&$form, $primary)
	{
		$object = $this->_entryExists($form->getValues());
		$object->fromArray($form->getValues());
		$object->save();

		return true;
	}

   /**
	* check, whether an entry exists
	*
	* This method gets called multiple times, e.g. when an existing
	* object gets updated. We'll therefor cache results locally using
	* a criteria string representation as hash.
	*
	* @access	private
	* @param	array
	*/
	public function _entryExists($values)
	{
		static $objects;
		$criteria = $this->getCriteria($values);
		$hash = $criteria->toString();

		if (isset($objects[$hash])) {
			return $objects[$hash];
		}

		$objects[$hash] = $this->peer->doSelectOne($criteria);

		if(empty($objects[$hash])) {
			return false;
		}
		return $objects[$hash];
	}
}
?>