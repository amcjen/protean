<?php
/**
 * simple Switch patForms element that builds and validates checkboxes.
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian Mordziol <argh@php-tools.net>
 */

/**
 * Warning: the clicklabel attribute needs the id attribute to be set.
 */
 define( 'PATFORMS_ELEMENT_SWITCH_WARNING_CLICKLABEL_NEEDS_ID', 'patForms:Element:Switch:01' );

/**
 * Warning: the clicklabel attribute needs the label attribute to be set.
 */
 define( 'PATFORMS_ELEMENT_SWITCH_WARNING_CLICKLABEL_NEEDS_LABEL', 'patForms:Element:Switch:02' );

/**
 * Warning: there is no default value for the given locale
 */
 define( 'PATFORMS_ELEMENT_SWITCH_WARNING_NO_DEFAULT_VALUE_AVAILABLE', 'patForms:Element:Switch:03' );

/**
 * simple Switch patForms element that builds and validates checkboxes.
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian Mordziol <argh@php-tools.net>
 * @license		LGPL, see license.txt for details
 */
class patForms_Element_Switch extends patForms_Element
{
   /**
	* Stores the name of the element - this is used mainly by the patForms
	* error management and should be set in every element class.
	* @access	public
	*/
	var $elementName	=	'Switch';

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
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"value"			=>	array(	"required"		=>	true,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"default"		=>	"yes"
									),
			"title"			=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"type"			=>	array(	"required"		=>	false,
										"format"		=>	"string",
										'default'		=>	'checkbox',
										"outputFormats"	=>	array( "html" ),
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
			"edit"			=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"yes",
										"outputFormats"	=>	array(),
									),
			"display"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"yes",
										"outputFormats"	=>	array(),
									),
			"required"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"yes",
										"outputFormats"	=>	array(),
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
			"accesskey"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
									),
			"position"		=>	array(	"required"		=>	false,
										"format"		=>	"int",
										"outputFormats"	=>	array(),
									),
			"tabindex"		=>	array(	"required"		=>	false,
										"format"		=>	"int",
										"outputFormats"	=>	array( "html" ),
									),
			"disabled"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"no",
										"outputFormats"	=>	array( "html" ),
									),
			"checked"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
									),
			"clicklabel"	=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array(),
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
			1	=>	"Please select the following field",
		),
		"de" =>	array(
			1	=>	"Pflichtfeld. Bitte wŠhlen Sie dieses Feld an.",
		),
		"fr" =>	array(
			1	=>	"Vous devez sŽlectionner ce champ.",
		)
	);

   /**
	* defines readonly display values for the switch element for the
	* available locales.
	*
	* @access	private
	* @var		array
	*/
	var $readonlyValues = array(
		'C' => array(
			'checked'	=>	'Yes',
			'unchecked'	=>	'No',
		),
		'de' => array(
			'checked'	=>	'Ja',
			'unchecked'	=>	'Nein',
		),
		'fr' => array(
			'checked'	=>	'Oui',
			'unchecked'	=>	'Non',
		),
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
		if( $this->attributes['display'] == 'no' )
		{
			return $this->createDisplaylessTag( $value );
		}

		if( $this->attributes["edit"] == "no" )
		{
			$this->attributes['disabled']	=	'yes';
		}

		if( isset( $this->attributes["value"] ) && $this->attributes["value"] == $value )
		{
			$this->attributes["checked"]	=	"checked";
		}

		$namespace = $this->getNamespace();
		if( isset( $this->attributes['id'] ) && $namespace ) {
			if (strpos($this->attributes['id'], $namespace . '_') !== 0) {
				$this->attributes['id'] = $namespace . '_' . $this->attributes['id'];
			}
		}

		$this->attributes["type"] = "checkbox";

		// clicklabel attribute
		if( isset( $this->attributes['clicklabel'] ) && $this->attributes['clicklabel'] == 'yes' )
		{
			// call this to initialize all attributes
			$this->getAttributesFor( $this->getFormat() );

			if( !isset( $this->attributes['id'] ) )
			{
				patErrorManager::raiseWarning(
					PATFORMS_ELEMENT_SWITCH_WARNING_CLICKLABEL_NEEDS_ID,
					'The "clicklabel" attribute needs the "id" attribute to be set.'
				);
			}
			else if( !isset( $this->attributes['label'] ) )
			{
				patErrorManager::raiseWarning(
					PATFORMS_ELEMENT_SWITCH_WARNING_CLICKLABEL_NEEDS_LABEL,
					'The "clicklabel" attribute needs the "label" attribute to be set.'
				);
			}
			else
			{
				$this->setAttribute( 'label', $this->createTag( 'label', 'full', array( 'for' => $this->getAttribute( 'id' ), 'title' => $this->getAttribute( 'title' ) ), $this->getAttribute( 'label' ) ) );
			}
		}

		 return $this->toHtml();
	}

   /**
	* element creation method for the 'HTML' format in the 'readonly' form mode.
	* Returns 'Yes' or 'No' as string, along with the hidden tag to keep the value.
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

		$state = 'unchecked';
		if( isset( $this->attributes["value"] ) && $this->attributes["value"] == $value )
		{
			$state = 'checked';
		}

		return $this->getReadonlyValue( $state ).$tag;
	}

   /**
	* Retrieves the value to display in the element's readonly mode if the
	* user has not selected any entry, according to the selected locale
	*
	* @access	public
	* @return	string	$defaultValue	The default readonly value in the needed locale
	*/
	function getReadonlyValue( $state )
	{
		$lang	=	$this->locale;

		if( !isset( $this->readonlyValues[$lang] ) )
		{
			patErrorManager::raiseWarning(
				PATFORMS_ELEMENT_SWITCH_WARNING_NO_DEFAULT_VALUE_AVAILABLE,
				'There is no default readonly value available for the locale "'.$lang.'", using default locale "C" instead.'
			);
			return $this->readonlyValues['C'][$state];
		}

		return $this->readonlyValues[$lang][$state];
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
		if( $this->getAttribute( 'required' ) == 'yes' && $value != $this->getAttribute( 'value' ) )
		{
			$this->addValidationError( 1 );
			return false;
		}
		
		return true;
	}
}

?>
