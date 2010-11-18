<?php
/**
 * Submit patForms element
 *
 * Upload-Element for single button. 
 *
 * Special attributes for this element
 *  - uploaddir: destination for the uploaded file
 *  - overwrite: Overwrite exitsing file if set to "yes"
 *  - permissions: Set file-permissions for destination file
 *  - usesession: Stores uploaded file information in session.
 *    Though the user may not upload a file twice of validation of other form elements faile
 *  - tempdir: The place to store uploaded file until finalization
 *  - mimetype: Defines as many allowed filetype as you want. Supports asterisk for groups like: 'image/*'
 *  - replacechars: use Perl-Regular expression for filename-translation
 *
 * $Id: Submit.php,v 1.1 2006/05/08 23:57:36 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 * @author		gERD Schaufelberger <gerd@php-tools.net>
 * @author		Sebastian Mordziol <argh@php-tools.net>
 * @todo		change html-output if file was already uploaded (use session)
 * @todo		make use of default attribute values
 * @todo		fix tempdir - attribute (default should work in Windows, too)
 */

/**
 * File upload field
 *
 * $Id: Submit.php,v 1.1 2006/05/08 23:57:36 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 * @author		gERD Schaufelberger <gerd@php-tools.net>
 * @author		Sebastian Mordziol <argh@php-tools.net>
 * @license		LGPL, see license.txt for details
 */
class patForms_Element_Submit extends patForms_Element
{
   /**
	* Stores the name of the element - this is used mainly by the patForms
	* error management and should be set in every element class.
	* @access	public
	*/
	var $elementName	=	"Submit";

   /**
	* the type of the element - set this to the type of element you are creating
	* if you want to use the {@link patForms_Element::element2html()} method to
	* create the final HTML tag for your element.
	*
	* @access	public
	* @see		patForms_Element::element2html()
	*/
	var $elementType	=	array(	"html"	=>	"input" );

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
			"type"			=>	array(	"required"		=>	false,
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
										"outputFormats"	=>	array( "html" ),
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
			"format"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array(),
									),
			"mimetype"		=>	array(	"required"		=>	false,
										"format"		=>	"array",
										"outputFormats"	=>	array(),
									),
			"uploaddir"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array(),
									),
			"overwrite"		=>	array(	"required"		=>	false,
										"format"		=>	"boolean",
										"outputFormats"	=>	array(),
									),
			"tempdir"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	false,
										"outputFormats"	=>	array(),
									),
			"permissions"	=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"0666",
										"outputFormats"	=>	array(),
									),
			"replacename"	=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	'i/[^a-z0-9\.]/_',
										"outputFormats"	=>	array(),
									),
			"usesession"	=>	array(	"required"		=>	false,
										"format"		=>	"boolean",
										"outputFormats"	=>	array(),
									),
			"disabled"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"no",
										"outputFormats"	=>	array( "html" ),
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
			1	=>	"Please enter the following information"
		),
		"de" =>	array(
			1	=>	"Pflichtfeld. Bitte vervollständigen Sie Ihre Angabe."
		),
		"fr" =>	array(
			1	=>	"Ce champ est obligatoire."
		)
	);

   /**
	* element creation method for the 'HTML' format in the 'default' form mode.
	*
	* @access	public
	* @param	mixed	value of the element
	* @return	mixed	$element	The element, or false if failed.
	* @todo		check, why the value has to be stored in the attributes
	*/
	function serializeHtmlDefault( $value )
	{
		if (!empty($value)) {
			$this->attributes['value']	=	$value;
		}
		$name		=	$this->attributes['name'];

		// make sure we're a file field :)
		$this->attributes['type']	=	'submit';

		// editable or not?
		if( isset( $this->attributes['edit'] ) && $this->attributes['edit'] == 'no' )
		{
			return $this->serializeHtmlReadonly( $value );
		}

		// create element
		$this->attributes['name']	=	$name;
		$element = $this->toHtml();
		if( patErrorManager::isError( $element ) )
		{
			return $element;
		}

		$this->attributes["name"]	=	$name;

		// and return to sender...
		return $element;
	}

   /**
	* element creation method for the 'HTML' format in the 'readonly' form mode.
	* Very simple; just returns the stored element value.
	*
	* @access	public
	* @param	mixed	value of the element
	* @return	string	$value	The element's value
	*/
	function serializeHtmlReadonly( $value )
	{
		$display	=	$value;

		$this->getAttributesFor( $this->getFormat() );

		return $display;
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
		return true;
	}
}
?>