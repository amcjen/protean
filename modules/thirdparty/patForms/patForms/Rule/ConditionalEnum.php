<?php
/**
 * patForms Rule ConditionalEnum
 *
 * $Id: ConditionalEnum.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Rules
 */

/**
 * patForms Rule ConditionalEnum
 *
 * This rule is used to change the values of an Enum
 * element depending on the value of any other field
 * in your form.
 *
 * It has to be applied after validating the form.
 *
 * @package		patForms
 * @subpackage	Rules
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Rule_ConditionalEnum extends patForms_Rule
{
   /**
	* script that will be displayed only once
	*
	* @access	private
	* @var		array
	*/
	var $globalScript	=	array(
									'html'	=>	'Html/Rule/ConditionalEnum.js'
								);

   /**
	* javascript that will be displayed once per instance
	*
	* @access	private
	* @var		array
	*/
	var $instanceScript	=	array(
										'html'	=>	"var pfr_[RULE::ID] = new pFRC_ConditionalEnum( '[RULE::SOURCE]', '[RULE::TARGET]' );\n"
									);

   /**
	* properties that have to be replaced in the instance script.
	*
	* @access	private
	* @var		array
	*/
	var $scriptPlaceholders	=	array(
									'RULE::SOURCE'	=>	'_source',
									'RULE::TARGET'	=>	'_target'
								);

   /**
	* define error codes and messages for the rule
	*
	* @access	private
	* @var		array	$validatorErrorCodes
    * @todo     translate error messages
	*/
	var	$validatorErrorCodes  =   array(
		"C"	=>	array(
			1	=>	"The selection in [TARGET_LABEL] does not match your selection in [SOURCE_LABEL].",
		),
		"de" =>	array(
			1	=>	"Ihre Auswahl in [TARGET_LABEL] passt nicht zur Auswahl in [SOURCE_LABEL].",
		),
		"fr" =>	array(
			1	=>	"La sŽlection dans [TARGET_LABEL] ne correspond pas ˆ votre sŽlection dans [SOURCE_LABEL].",
		)
	);

   /**
	* source field
	*
	* @access	private
	* @var		string
	*/
	var $_source;

   /**
	* target field
	*
	* @access	private
	* @var		string
	*/
	var $_target;

   /**
	* conditions
	*
	* @access	private
	* @var		array
	*/
	var $_conditions	=	array();

   /**
	* prepare the rule
	*
	* @access	public
	* @param	object patForms
	*/
	function prepareRule(&$container)
	{
        patForms_Rule::prepareRule( $container );
		
        $source = &$container->getElementByName( $this->_source );
        $onChange = $source->getAttribute( 'onchange' );
		
        $newHandler	= sprintf('pfr_%s.adjustTarget();', $this->_id);
		$source->setAttribute('onchange', $newHandler . $onChange);		
		return true;
	}

   /**
	* set the name of the source field
	*
	* @access	public
	* @param	string	source field for the condition
	*/
	function setSourceField($field)
	{
		$this->_source = $field;
	}

   /**
	* set the name of the target field
	*
	* @access	public
	* @param	string	target field for the condition
	*/
	function setTargetField($field)
	{
		$this->_target = $field;
	}

   /**
	* add a condition
	*
	* @access	public
	* @param	string	condition value
	* @param	mixed	options for the enum
	*/
	function addCondition($value, $options)
	{
		$this->_conditions[$value] = $options;
	}

   /**
	* method called by patForms 
	*
	* @access	public
	* @param	object patForms	form object
	*/
	function applyRule(&$form, $type = PATFORMS_RULE_AFTER_VALIDATION)
	{
		$source	= $form->getElementByName($this->_source);
		$target	= $form->getElementByName($this->_target);

		$sourceVal = $source->getValue();

		if (!isset($this->_conditions[$sourceVal])) {
			return true;
		}

		$values = $this->_conditions[$sourceVal];
		if (in_array( $target->getValue(), $values )) {
			return true;
		}

		$vars = array(
                    'SOURCE_LABEL' => $source->getAttribute('label'),
                    'TARGET_LABEL' => $target->getAttribute('label'),
                    );

		$this->addValidationError(1, $vars);
		return false;
	}

   /**
	* get the instance javascript of the rule
	*
	* @access	public
	* @return	string
	*/
	function getInstanceJavascript()
	{
		$script = patForms_Rule::getInstanceJavascript();

		if ($script === false) {
			return false;
		}
		
		foreach ($this->_conditions as $value => $options) {
			$list = array();
			foreach ($options as $option) {
				array_push($list, "'$option'");
			}
			$script .= sprintf("pfr_%s.addCondition( '%s', new Array( %s ) );\n", $this->_id, $value, implode(',', $list));
		}			
		return $script;
	}
}
?>