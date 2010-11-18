<?php
/**
 * patForms Rule that does not allow new entries to be added to a form
 *
 * $Id: StorageProhibitAdd.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Rules
 */

/**
 * patForms Rule that does not allow new entries to be added to a form
 *
 * @package		patForms
 * @subpackage	Rules
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Rule_StorageProhibitAdd extends patForms_Rule
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
			1	=>	'The entry does not exist.',
		),
		'de' =>	array(
			1	=>	'Der Eintrag existiert nicht.',
		),
		'fr' =>	array(
			1	=>	'Cette entrée n\'existe pas.',
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
	function applyRule(&$form, $type = PATFORMS_AFTER_VALIDATION)
	{
	    if ($this->_storage->entryExists(&$form) === false) {
	        $this->addValidationError(1);
		    return false;
        }
        return true;
	}
}
?>