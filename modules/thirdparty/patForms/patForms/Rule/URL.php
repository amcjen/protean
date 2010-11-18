<?php
/**
 * patForms Rule URL
 *
 * $Id: URL.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Rules
 */

/**
 * patForms Rule URL
 *
 * @package		patForms
 * @subpackage	Rules
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Rule_URL extends patForms_Rule
{
   /**
	* name of the rule
	*
	* @abstract
	* @access	private
	*/
	var	$ruleName = 'URL';

   /**
	* define error codes and messages for the rule
	*
	* @access	private
	* @var		array	$validatorErrorCodes
    * @todo     translate error messages
	*/
	var	$validatorErrorCodes  =   array(
        "C"   =>   array(
             1   =>   "Please ensure the URL is valid",
        ),
		"de" =>	array(
			1	=>	"Die URL hat ein ungŸltiges Format.",
		),
		"fr"=>	array(
             1   =>   "L'URL a une syntaxe incorrecte.",
		),
	);

   /**
	* method called by patForms or any patForms_Element to validate the
	* element or the form.
	*
	* @access	public
	* @param	object patForms	form object
	*/
	function applyRule(&$element, $type = PATFORMS_RULE_BEFORE_VALIDATION)
	{
		$value = $element->getValue();
		if (empty($value)) {
			return true;
		}
		
		$preg = '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i';
		
		//$preg = "^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]";
		
		if (!preg_match($preg, $value)) {
			
			$value = 'http://' . $value;
			if (!preg_match($preg, $value)) {
				$this->addValidationError(1);
				return false;
			}
		}
		
		$element->setValue($value);
		return true;
	}
}
?>