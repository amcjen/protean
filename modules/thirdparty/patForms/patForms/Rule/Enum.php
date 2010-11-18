<?php
/**
 * patForms Rule Enum
 *
 * $Id: Enum.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Rules
 */

/**
 * patForms Rule Enum
 *
 * This is just a simple rule, that makes any field behave
 * like an Enum field.
 *
 * @package		patForms
 * @subpackage	Rules
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Rule_Enum extends patForms_Rule
{
   /**
	* script that will be displayed only once
	*
	* @access	private
	* @var		array
	*/
	var $globalScript	=	array(
										'html'	=>	"/* patForms::Rule::Enum */
function pFRC_Enum( field )
{
	this.field = eval( 'pfe_' + field );
	this.values = new Array();
}

pFRC_Enum.prototype.validate	= function()
{
	value	=	this.field.getValue();
	for( var i = 0; i < this.values.length; i++ )
	{
		if( this.values[i] == value )
			return true;
	}
	alert( 'Ungültiger Wert!' );
}

pFRC_Enum.prototype.setValues	= function( values )
{
	this.values	=	values;
}

/* END: patForms::Rule::Enum */
"
									);

   /**
	* javascript that will be displayed once per instance
	*
	* @access	private
	* @var		array
	*/
	var $instanceScript	=	array(
										'html'	=>	"var pfr_[RULE::ID] = new pFRC_Enum( '[CONTAINER::NAME]' );\n"
									);

   /**
	* properties that have to be replaced in the instance script.
	*
	* @access	private
	* @var		array
	*/
	var $scriptPlaceholders	=	array(
									'RULE::SOURCE'	=>	'_source',
								);

   /**
	* name of the rule
	*
	* @abstract
	* @access	private
	*/
	var	$ruleName = 'Enum';

   /**
	* define error codes and messages for the rule
	*
	* @access	private
	* @var		array	$validatorErrorCodes
    * @todo     translate error messages
	*/
	var	$validatorErrorCodes  =   array(
		"C"	=>	array(
			1	=>	"Please enter only one of the following values: [ENUM_VALUES]",
		),
		"de" =>	array(
			1	=>	"Bitte geben Sie einen der folgenden Werte ein: [ENUM_VALUES]",
		),
		"fr" =>	array(
			1	=>	"Veuillez n'entrer que l'une des valeurs suivantes: [ENUM_VALUES]",
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

   /**
	* prepare the rule
	*
	* @access	public
	* @param	object patForms
	*/
	function prepareRule( &$container )
	{
		patForms_Rule::prepareRule( $container );
		
		$onChange	=	$container->getAttribute( 'onchange' );
		
		$newHandler	=	sprintf( 'pfr_%s.validate();', $this->_id );
		
		$container->setAttribute( 'onchange', $newHandler . $onChange );	
		
		return true;
	}

   /**
	* set the values
	*
	* @access	public
	* @param	array	values
	*/
	function setValues( $values )
	{
		$this->_values	=	$values;
	}

   /**
	* method called by patForms or any patForms_Element to validate the
	* element or the form.
	*
	* @access	public
	* @param	object patForms	form object
	*/
	function applyRule( &$element, $type = PATFORMS_RULE_BEFORE_VALIDATION )
	{
		if( in_array( $element->getValue(), $this->_values ) )
			return	true;
	
		$vars	=	array(
							'enum_values'	=>	implode( ', ', $this->_values )
						);
		
		$this->addValidationError( 1, $vars );
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
		$script	=	patForms_Rule::getInstanceJavascript();

		if( $script === false )
		{
			return false;
		}
		
		$list	=	array();
		foreach( $this->_values as $value )
		{
			array_push( $list, "'$value'" );
		}
		$script	.=	sprintf( "pfr_%s.setValues( new Array( %s ) );\n", $this->_id, implode( ',', $list ) );
			
		return $script;
	}
}
?>