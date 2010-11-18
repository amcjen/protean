<?php
/**
 * patForms Rule German Zip Code
 *
 * $Id: Telephone.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Rules
 */

/**
 * patForms Rule German Zip Code
 *
 * This only checks for the format of a german zip code.
 *
 * @package		patForms
 * @subpackage	Rules
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Rule_Telephone extends patForms_Rule
{
   /**
	* name of the rule
	*
	* @abstract
	* @access	private
	*/
	var	$ruleName = 'Telephone';

   /**
	* define error codes and messages for the rule
	*
	* @access	private
	* @var		array	$validatorErrorCodes
    * @todo     translate error messages
	*/
	var	$validatorErrorCodes  =   array(
		"C"	=>	array(
			1	=>	"The value is not a valid telephone number.",
		),
		"de" =>	array(
			1	=>	"Der Wert ist keine Postleitzahl.",
		),
		"fr" =>	array(
			1	=>	"La valeur entrŽe n'est pas un code postal allemand valide.",
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
		if (preg_match('/^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$|^\s*$/', $element->getValue())) {
			return true;
		}
		$this->addValidationError( 1 );
		return false;	
	}
}
?>