<?php
/**
 * patForms Rule German BLZ Code
 *
 * $Id: GermanBLZ.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Rules
 */

/**
 * patForms Rule German BLZ Code
 *
 * This only checks for the format of a german BLZ code.
 *
 * @package		patForms
 * @subpackage	Rules
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Rule_GermanBLZ extends patForms_Rule
{
   /**
	* name of the rule
	*
	* @abstract
	* @access	private
	*/
	var	$ruleName = 'GermanBLZ';

   /**
	* define error codes and messages for the rule
	*
	* @access	private
	* @var		array	$validatorErrorCodes
    * @todo     translate error messages
	*/
	var	$validatorErrorCodes  =   array(
		"C"	=>	array(
			1	=>	"The value is not a german bank code.",
		),
		"de" =>	array(
			1	=>	"Der Wert ist keine deutsche Bankleitzahl.",
		),
		"fr" =>	array(
			1	=>	"La valeur entrée n'est pas un code bancaire allemand valide.",
		)
	);

   /**
	* method called by patForms or any patForms_Element to validate the
	* element or the form.
	*
	* @access	public
	* @param	object patForms	form object
	*/
	function applyRule( &$element, $type = PATFORMS_RULE_BEFORE_VALIDATION )
	{
		if (preg_match('/[0-9]{8}/', $element->getValue())) {
			return true;
		}
		$this->addValidationError( 1 );
		return false;	
	}
}
?>