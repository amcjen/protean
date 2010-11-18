<?php
/**
 * patForms Rule Retype
 *
 * $Id: Retype.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Rules
 */

/**
 * patForms Rule Retype
 *
 * Implements the popular 'retype feature' to
 * ensure a password does not contain any typos.
 *
 * @package		patForms
 * @subpackage	Rules
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Rule_Retype extends patForms_Rule
{
   /**
	* define error codes and messages for the rule
	*
	* @access	private
	* @var		array	$validatorErrorCodes
    * @todo     translate error messages
	*/
	var	$validatorErrorCodes  =   array(
		"C"	=>	array(
			1	=>	"The fields '[FIELD1_LABEL]' and '[FIELD2_LABEL]' do not match.",
		),
		"de" =>	array(
			1	=>	"Ihre Angaben in den Feldern '[FIELD1_LABEL]' und '[FIELD2_LABEL]' stimmen nicht überein.",
		),
		"fr" =>	array(
			1	=>	"Les champs '[FIELD1_LABEL]' et '[FIELD2_LABEL]' ne correspondent pas.",
		)
	);

   /**
	* fields that have to match
	* @access	private
	* @var		array
	*/
	var $_fieldNames;

   /**
	* set the names of the fields that have to match
	*
	* @access	public
	* @param	string	field 1
	* @param	string	field 2
	*/
	function setFieldnames( $field1, $field2 )
	{
		$this->_fieldNames	=	array( $field1, $field2 );
	}

   /**
	* method called by patForms or any patForms_Element to validate the
	* element or the form.
	*
	* @access	public
	* @param	object patForms	form object
	*/
	function applyRule( &$form, $type = PATFORMS_RULE_BEFORE_VALIDATION )
	{
		$el1	=	&$form->getElement( $this->_fieldNames[0] );
		$el2	=	&$form->getElement( $this->_fieldNames[1] );

		if( $el1->getValue() == $el2->getValue() )
		{
			return true;
		}

		$vars	=	array(
							'field1_label'	=>	$el1->getAttribute( 'label' ),
							'field2_label'	=>	$el2->getAttribute( 'label' )
						);
		
		$this->addValidationError( 1, $vars );
		return false;	
	}
}
?>