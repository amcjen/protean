<?php
/**
 * patForms Rule ConditionalRequired
 *
 * $Id: ConditionalRequired.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Rules
 */

/**
 * patForms Rule ConditionalRequired
 *
 * This rule can be used to set the status of
 * some elements to required depending on the value
 * of another element.
 *
 * It has to be applied prior to validating the form.
 *
 * @package		patForms
 * @subpackage	Rules
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Rule_ConditionalRequired extends patForms_Rule
{
   /**
	* fields that will be required
	* @access	private
	* @var		array
	*/
	var $_requiredFields	=	array();

   /**
	* conditions
	* @access	private
	* @var		array
	*/
	var $_conditions		=	array();

   /**
	* set the names of the fields that will be required
	*
	* @access	public
	* @param	array	required fields
	*/
	function setRequiredFields( $fields )
	{
		$this->_requiredFields	=	$fields;
	}

   /**
	* add a condition
	*
	* @access	public
	* @param	string	condition field name
	* @param	mixed	condition value
	*/
	function addCondition( $field, $value )
	{
		$this->_conditions[$field]	=	$value;
	}

   /**
	* method called by patForms or any patForms_Element to validate the
	* element or the form. ETJ - This does an AND combination, not an OR
	*
	* @access	public
	* @param	object patForms	form object
	*/
	function applyRule( &$form, $type = PATFORMS_RULE_BEFORE_VALIDATION )
	{
		$required	=	'no';
		foreach( $this->_conditions as $field => $value )
		{
			$el		=	$form->getElement( $field );
			$val	=	$el->getValue();
			
			if( $val == $value )
			{
				$required	=	'yes';
				break;
			}
		}

		foreach( $this->_requiredFields as $field )
		{
			$el	=	$form->getElement( $field );
			$el->setAttribute( 'required', $required );
		}
		
		return	true;
	}
}
?>