<?php
/**
 * patForms Rule NotMatch
 *
 * This rule matches the field value against a regEx pattern. It successfully
 * validates the passed value if the value does *not* match the pattern.
 *
 * $Id: NotMatch.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Rules
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */

/**
 * patForms Rule NotMatch
 *
 * This rule matches the field value against a regEx pattern. It successfully
 * validates the passed value if the value does *not* match the pattern.
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Rules
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */
class patForms_Rule_NotMatch extends patForms_Rule
{
	/**
	* script that will be displayed only once
	*
	* @access	private
	* @var		array
	*/

	var $globalScript = array(
		'html'	=>	'Html/Rule/NotMatch.js'
	);

	/**
	* javascript that will be displayed once per instance
	*
	* @access	private
	* @var		array
	*/
	var $instanceScript	= array(
		'html'	=>	"var pfr_[RULE::ID] = new pFRC_NotMatch('[CONTAINER::NAME]');\n"
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
	var	$ruleName = 'NotMatch';

	/**
	* define error codes and messages for the rule
	*
	* @access	private
	* @var		array	$validatorErrorCodes
	* @todo		translate error messages
	*/
	var	$validatorErrorCodes = array(
		"C"	=>	array(
			1	=>	"This is an invalid value.",
		),
		"de" =>	array(
			1	=>	"Dies ist ein ungültiger Wert.",
		),
		"fr" =>	array(
			1	=>	"Valeur incorrecte.",
		)
	);

	/**
	* the value to compare with, a regEx pattern in this case
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
	* set the value to compare with, a regEx pattern in this case
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
		if (preg_match($this->_value, $element->getValue()) == 0){
			return	true;
		}

		$this->addValidationError(1);
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

		$script = sprintf("pfr_%s.setValue(%s);\n", $this->_id, $this->_value);
		$form->registerInstanceJavascript($script);
	}
}
?>