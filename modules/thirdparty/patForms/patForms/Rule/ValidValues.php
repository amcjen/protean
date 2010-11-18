<?php
/**
 * patForms Rule ValidValues
 *
 * A simple rule, that checks for a required minimum length of a field.
 * Required for integration with Propel but probably useful otherwise.
 *
 * $Id: ValidValues.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Rules
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */

/**
 * patForms Rule ValidValues
 *
 * A simple rule, that checks for a required minimum length of a field.
 * Required for integration with Propel but probably useful otherwise.
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Rules
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */
class patForms_Rule_ValidValues extends patForms_Rule
{
	/**
	* script that will be displayed only once
	*
	* @access	private
	* @var		array
	*/
	var $globalScript = array(
		'html'	=>	'Html/Rule/ValidValue.js'
	);

	/**
	* javascript that will be displayed once per instance
	*
	* @access	private
	* @var		array
	*/
	var $instanceScript	= array(
		'html'	=>	"var pfr_[RULE::ID] = new pFRC_ValidValue('[CONTAINER::NAME]');\n"
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
	var	$ruleName = 'ValidValue';

	/**
	* define error codes and messages for the rule
	*
	* @access	private
	* @var		array	$validatorErrorCodes
	* @todo		translate error messages
	*/
	var	$validatorErrorCodes = array(
		"C"	=>	array(
			1	=>	"Please enter one of the following values: [VALUES].",
		),
		"de" =>	array(
			1	=>	"Bitte geben Sie einen der folgenden Werte ein: [VALUES].",
		),
		"fr" =>	array(
			1	=>	"Veuillez n'entrer que l'une des valeurs suivantes: [VALUES].",
		)
	);

	/**
	* possible values
	* @access	private
	* @var		array
	*/
	var $_values;

	/**
	* field id that is used
	* @access	private
	* @var		string
	*/
	var $_field;

	public function __construct($params)
	{
		parent::__construct();

		extract($params);
		$this->_values = explode('|', $value);
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
		if(in_array($element->getValue(), $this->_values)) {
			return	true;
		}

		$this->addValidationError(1, array('values' => implode(', ', $this->_values)));
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

		foreach ($this->_values as $value) {
			$values[] = "'$value'";
		}
		$script = sprintf("pfr_%s.setValues(new Array(%s));\n", $this->_id, implode(', ', $values));
		$form->registerInstanceJavascript($script);
	}
}
?>