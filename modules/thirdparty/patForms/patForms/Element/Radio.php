<?php
/**
 * simple radiobutton patForms element that builds and validates radio buttons, with the
 * particularity that it does not generate a fully serialized element, but an array with
 * serialized subelements.
 *
 * $Id: Radio.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 */

/**
 * Error: no default value is available for the element.
 */
 define( 'PATFORMS_ERROR_RADIO_NO_DEFAULT_VALUE_AVAILABLE', 7001 );

/**
 * Warning: the clicklabel attribute needs the id attribute to be set.
 */
 define( 'PATFORMS_WARNING_RADIO_CLICKLABEL_NEEDS_ID', 7002 );

/**
 * Warning: the clicklabel attribute needs the label attribute to be set.
 */
 define( 'PATFORMS_WARNING_RADIO_CLICKLABEL_NEEDS_LABEL', 7003 );

/**
 * simple radiobutton patForms element that builds and validates radio buttons, with the
 * particularity that it does not generate a fully serialized element, but an array with
 * serialized subelements.
 *
 * $Id: Radio.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian Mordziol <argh@php-tools.net>
 * @license		LGPL, see license.txt for details
 */
class patForms_Element_Radio extends patForms_Element
{
   /**
	* Stores the name of the element - this is used mainly by the patForms
	* error management and should be set in every element class.
	* @access	public
	*/
	var $elementName	=	'Radio';

   /**
	* the type of the element - set this to the type of element you are creating
	* if you want to use the {@link patForms_Element::element2html()} method to
	* create the final HTML tag for your element.
	*
	* @access	public
	* @see		patForms_Element::element2html()
	*/
	var $elementType	=	array(	"html"	=>	"input",
								);

   /**
	* The radio element uses a renderer to serialize its content, so we set the flag
	* to true here
	*
	* @access	private
	* @var		boolean
	*/
	var $usesRenderer	=	false;

   /**
	* set here which attributes you want to include in the element if you want to use
	* the {@link patForms_Element::convertDefinition2Attributes()} method to automatically
	* convert the values from your element definition into element attributes.
	*
	* @access	protected
	* @see		patForms_Element::convertDefinition2Attribute()
	*/
	var	$attributeDefinition	=	array(

			"id"			=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
									),
			"name"			=>	array(	"required"		=>	true,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
									),
			"type"			=>	array(	"required"		=>	true,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
									),
			"title"			=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"description"	=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array(),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"default"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array(),
									),
			"label"			=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array(),
									),
			"display"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"yes",
										"outputFormats"	=>	array(),
									),
			"edit"			=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"yes",
										"outputFormats"	=>	array(),
									),
			"required"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"yes",
										"outputFormats"	=>	array(),
									),
			"value"			=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( 'html' ),
									),
			"style"			=>	array(	"required"		=>	false,
										"outputFormats"	=>	array( "html" ),
										"format"		=>	"string",
									),
			"class"			=>	array(	"required"		=>	false,
										"outputFormats"	=>	array( "html" ),
										"format"		=>	"string",
									),
			"onchange"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"onclick"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"onfocus"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"onmouseover"	=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"onmouseout"	=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"onblur"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"position"		=>	array(	"required"		=>	false,
										"format"		=>	"int",
										"outputFormats"	=>	array(),
									),
			"values"		=>	array(	"required"		=>	false,
										"format"		=>	"values",
										"outputFormats"	=>	array(),
									),
			"disabled"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"no",
										"outputFormats"	=>	array( "html" ),
									),
			"clicklabel"	=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array(),
									),
			"checked"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( 'html' ),
									),
		);

    /**
     *	define error codes an messages for each form element
     *
     *  @access private
     *  @var	array	$validatorErrorCodes
     */
	var	$validatorErrorCodes  =   array(
		"C"	=>	array(
			1	=>	"Please check the following field",
		),
		"de" =>	array(
			1	=>	"Diese Option muss gewählt werden.",
		),
		"fr" =>	array(
			1	=>	"Cette option doit être sélectionnée.",
		)
	);

   /**
	* element creation method for the 'HTML' format in the 'default' form mode.
	*
	* @access	public
	* @param	mixed	value of the element
	* @return	mixed	$element	The element, or false if failed.
	*/
	function serializeHtmlDefault( $value )
	{
		$this->attributes["type"]	=	"radio";

		if( $this->attributes['display'] == 'no' )
		{
			return $this->createDisplaylessTag( $value );
		}

		if( $value == $this->attributes["value"] )
		{
			$this->attributes['checked'] = 'checked';
		}

		if( isset( $this->attributes["edit"] ) && $this->attributes["edit"] == "no" )
		{
			$this->attributes['disabled']	=	'yes';
		}

		$namespace = $this->getNamespace();
		if( isset( $this->attributes['id'] ) && $namespace ) {
			if (strpos($this->attributes['id'], $namespace . '_') !== 0) {
				$this->attributes['id'] = $namespace . '_' . $this->attributes['id'];
			}
		}

		// clicklabel attribute
		if( isset( $this->attributes['clicklabel'] ) && $this->attributes['clicklabel'] == 'yes' )
		{
			// call this to initialize all attributes
			$this->getAttributesFor( $this->getFormat() );

			if( !isset( $this->attributes['id'] ) )
			{
				patErrorManager::raiseWarning(
					PATFORMS_WARNING_RADIO_CLICKLABEL_NEEDS_ID,
					'The "clicklabel" attribute needs the "id" attribute to be set.'
				);
			}
			else if( !isset( $this->attributes['label'] ) )
			{
				patErrorManager::raiseWarning(
					PATFORMS_WARNING_RADIO_CLICKLABEL_NEEDS_LABEL,
					'The "clicklabel" attribute needs the "label" attribute to be set.'
				);
			}
			else
			{
				$this->setAttribute( 'label', $this->createTag( 'label', 'full', array( 'for' => $this->getAttribute( 'id' ), 'title' => $this->getAttribute( 'title' ) ), $this->getAttribute( 'label' ) ) );
			}
		}

		// create element
		return $this->toHtml();
	}

   /**
	* element creation method for the 'HTML' format in the 'readonly' form mode.
	*
	* @access	public
	* @param	mixed	value of the element
	* @return	string	$value	The element's value
	*/
	function serializeHtmlReadonly( $value )
	{
		$tag = $this->createDisplaylessTag( $value );

		if( $this->attributes['display'] == 'no' )
		{
			return $tag;
		}

		return $this->attributes['label'].$tag;
	}

   /**
	* Retrieves the default value to display in the element's readonly mode if the
	* user has not selected any entry, according to the selected locale
	*
	* @access	public
	* @return	string	$defaultValue	The default readonly value in the needed locale
	*/
	function getReadonlyDefaultValue()
	{
		$lang	=	$this->locale;

		if( !isset( $this->defaultReadonlyValue[$lang] ) )
		{
			patErrorManager::raiseWarning(
				PATFORMS_ERROR_RADIO_NO_DEFAULT_VALUE_AVAILABLE,
				'There is no default readonly value available for the locale "'.$lang.'", using default locale "C" instead.'
			);
			return $this->defaultReadonlyValue['C'];
		}

		return $this->defaultReadonlyValue[$lang];
	}

   /**
	* validates the element.
	*
	* @access	public
	* @param	mixed	value of the element
	* @return	bool	$isValid	True if element could be validated, false otherwise.
	*/
	function validateElement( $value )
	{
		if( $this->attributes['required'] == 'yes' && empty( $value ) )
		{
			$this->addValidationError( 1 );
			return false;
		}

		return true;
	}
}

?>
