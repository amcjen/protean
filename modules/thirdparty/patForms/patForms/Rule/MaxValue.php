<?php
/**
 * patForms Rule MaxValue
 *
 * This rule simply checks for a required maximum value (number) of a field
 *
 * $Id: MaxValue.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Rules
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */

/**
 * patForms Rule MaxValue
 *
 * This rule simply checks for a required maximum value (number) of a field
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Rules
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */
class patForms_Rule_MaxValue extends patForms_Rule
{
	/**
	* script that will be displayed only once
	*
	* @access	private
	* @var		array
	*/

	var $globalScript = array(
		'html'	=>	'Html/Rule/MinLength.js'
	);

	/**
	* javascript that will be displayed once per instance
	*
	* @access	private
	* @var		array
	*/
	var $instanceScript	= array(
		'html'	=>	"var pfr_[RULE::ID] = new pFRC_MaxValue('[CONTAINER::NAME]');\n"
	);

	/**
	* properties that have to be replaced in the instance script.
	*
	* @access	private
	* @var		array
	*/
	var $scriptPlaceholders	= array(
		'RULE::SOURCE'	=>	'_source',
	);

	/**
	* name of the rule
	*
	* @abstract
	* @access	private
	*/
	var	$ruleName = 'MaxValue';

	/**
	* define error codes and messages for the rule
	*
	* @access	private
	* @var		array	$validatorErrorCodes
	* @todo		translate error messages
	*/
	var	$validatorErrorCodes = array(
		"C"	=>	array(
			1	=>	"Please enter a number that is less or equal to [VALUE].",
		),
		"de" =>	array(
			1	=>	"Bitte geben Sie eine Zahl kleiner oder gleich [VALUE] ein.",
		),
		"fr" =>	array(
			1	=>	"Veuillez entrer une valeur n'excédant pas [VALUE].",
		)
	);

	/**
	* the value to compare with
	* @access	private
	* @var		string
	*/
	var $_value;

	/**
	* field id that is used
	* @access	private
	* @var		string
	*/
	var $_field;

	/**
	* set the value to compare with
	*
	* @access	public
	* @param	object patForms
	*/

	function setValue($value)
	{
		$this->_value = $value;
	}

	/**
	* prepare the rule
	*
	* @access	public
	* @param	object patForms
	*/
	function prepareRule(&$container)
	{
		patForms_Rule::prepareRule($container);

		$onChange = $container->getAttribute('onchange');
		$newHandler = sprintf('pfr_%s.validate();', $this->_id);
		$container->setAttribute('onchange', $newHandler . $onChange);

		return true;
	}

	/**
	* method called by patForms or any patForms_Element to validate the
	* element or the form.
	*
	* @access	public
	* @param	object patForms	form object
	*/
	function applyRule(&$element, $type = PATFORMS_RULE_AFTER_VALIDATION)
	{
		if (intval($element->getValue()) <= intval($this->_value)){
			return	true;
		}

		$this->addValidationError(1, array('value' => $this->_value));
		return false;
	}

	/**
	* Registers scripts form this rule to the form
	*
	* @access	public
	* @param	object patForms	form object
	*/
	function registerJavascripts(&$form)
	{
		parent::registerJavascripts($form);

		$script = sprintf("pfr_%s.setMaxValue(%s);\n", $this->_id, $this->_value);
		$form->registerInstanceJavascript($script);
	}
}
?>