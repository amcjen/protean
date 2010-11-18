<?php
/**
 * patForms Rule Retype
 *
 * $Id: StorageProhibitUpdate.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Rules
 */

/**
 * patForms Rule NoUpdate
 *
 * Checks whether an entry exists and permits update of it by using the storage
 * container which is responsible for saving.
 *
 * @package		patForms
 * @subpackage	Rules
 * @author		Frank Kleine <frank.kleine@schlund.de>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Rule_StorageProhibitUpdate extends patForms_Rule
{
   /**
	* define error codes and messages for the rule
	*
	* @access	private
	* @var		array	$validatorErrorCodes
    * @todo     translate error messages
	*/
	var	$validatorErrorCodes  =   array(
		'C'	=>	array(
			1	=>	'An entry with your data exists.',
		),
		'de' =>	array(
			1	=>	'Es besteht bereits ein Eintrag mit Ihren Daten.',
		),
		'fr' =>	array(
			1	=>	'Une entrée existe déjà pour vos données.',
		)
	);

   /**
	* the storage container
	* @access	private
	* @var		array
	*/
	var $_storage;

   /**
	* set the storage that can deliver if the entry exists
	*
	* @access	public
	* @param	object	storage
	*/
	function setStorage(&$storage)
	{
		$this->_storage	=& $storage;
	}

   /**
	* method called by patForms or any patForms_Element to validate the
	* element or the form.
	*
	* @access	public
	* @param	object patForms	form object
	*/
	function applyRule(&$form, $type = PATFORMS_RULE_AFTER_VALIDATION)
	{
	    if ($this->_storage->entryExists(&$form) === true) {
	        $this->addValidationError(1);
		    return false;
        }
        return true;
	}
}
?>