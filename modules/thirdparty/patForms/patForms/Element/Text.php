<?php
/**
 * simple textfield patForms element that builds and validates text input fields.
 * 
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian Mordziol <argh@php-tools.net>
 */

/**
 * simple textfield patForms element that builds and validates text input fields.
 * 
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian Mordziol <argh@php-tools.net>
 * @license		LGPL, see license.txt for details
 */
class patForms_Element_Text extends patForms_Element
{
   /**
	* Stores the name of the element - this is used mainly by the patForms
	* error management and should be set in every element class.
	* @access	public
	*/
	var $elementName = 'Text';

   /**
	* the type of the element - set this to the type of element you are creating
	* if you want to use the {@link patForms_Element::element2html()} method to 
	* create the final HTML tag for your element.
	*
	* @access	public
	* @see		patForms_Element::element2html()
	*/
	var $elementType	=	array(	
		'html'	=>	'input',
	);
	
   /**
	* set here which attributes you want to include in the element if you want to use
	* the {@link patForms_Element::convertDefinition2Attributes()} method to automatically
	* convert the values from your element definition into element attributes.
	*
	* @access	protected
	* @see		patForms_Element::convertDefinition2Attribute()
	*/
	var	$attributeDefinition = array(	
			
		'id' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'outputFormats'	=>	array( 'html' ),
		),
		'name' => array(	
			'required'		=>	true,
			'format'		=>	'string',
			'outputFormats'	=>	array( 'html' ),
			'modifiers'		=>	array( 'insertSpecials' => array() ),
		),
		'title' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'outputFormats'	=>	array( 'html' ),
			'modifiers'		=>	array( 'insertSpecials' => array() ),
		),
		'description' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'outputFormats'	=>	array(),
			'modifiers'		=>	array( 'insertSpecials' => array() ),
		),
		'default' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'outputFormats'	=>	array(),
		),
		'label' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'outputFormats'	=>	array(),
		),
		'edit' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'default'		=>	'yes',
			'outputFormats'	=>	array(),
		),
		'display' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'default'		=>	'yes',
			'outputFormats'	=>	array(),
		),
		'required' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'default'		=>	'yes',
			'outputFormats'	=>	array(),
		),
		'value' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'outputFormats'	=>	array( 'html' ),
		),
		'style' => array(	
			'required'		=>	false,
			'outputFormats'	=>	array( 'html' ),
			'format'		=>	'string',
		),
		'class' => array(	
			'required'		=>	false,
			'outputFormats'	=>	array( 'html' ),
			'format'		=>	'string',
		),
		'onchange' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'outputFormats'	=>	array( 'html' ),
			'modifiers'		=>	array( 'insertSpecials' => array() ),
		),
		'onclick' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'outputFormats'	=>	array( 'html' ),
			'modifiers'		=>	array( 'insertSpecials' => array() ),
		),
		'onfocus' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'outputFormats'	=>	array( 'html' ),
			'modifiers'		=>	array( 'insertSpecials' => array() ),
		),
		'onmouseover' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'outputFormats'	=>	array( 'html' ),
			'modifiers'		=>	array( 'insertSpecials' => array() ),
		),
		'onmouseout' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'outputFormats'	=>	array( 'html' ),
			'modifiers'		=>	array( 'insertSpecials' => array() ),
		),
		'onblur' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'outputFormats'	=>	array( 'html' ),
			'modifiers'		=>	array( 'insertSpecials' => array() ),
		),
		'accesskey' => array(	
			'required'		=>	false,
			'format'		=>	'string',
			'outputFormats'	=>	array( 'html' ),
		),
		'position' => array(	
			'required'		=>	false,
			'format'		=>	'int',
			'outputFormats'	=>	array(),
		),
		'tabindex' => array(	
			'required'		=>	false,
			'format'		=>	'int',
			'outputFormats'	=>	array( 'html' ),
		),
		'maxlength' => array(
			'required'		=>	false,
			'format'		=>	'int',
			'outputFormats'	=>	array(),
		),
		'minlength' => array(
			'required'		=>	false,
			'format'		=>	'int',
			'outputFormats'	=>	array(),
		),
		'rows' => array(
			'required'		=>	false,
			'format'		=>	'int',
			'outputFormats'	=>	array( 'html' ),
		),
		'cols' => array(
			'required'		=>	false,
			'format'		=>	'int',
			'outputFormats'	=>	array( 'html' ),
		),
		'allowedtags' => array(
			'required'		=>	false,
			'format'		=>	'string',
			'default'		=>	'',
			'outputFormats'	=>	array( ),
		),
		'deniedtags' => array(
			'required'		=>	false,
			'format'		=>	'string',
			'default'		=>	'*',
			'outputFormats'	=>	array( ),
		),
		'disabled' => array(
			'required'		=>	false,
			'format'		=>	'string',
			'default'		=>	'no',
			'outputFormats'	=>	array( 'html' ),
		),
	);
		
    /**
     *	define error codes and messages for each form element
     *
     *  @access private
     *  @var	array	$validatorErrorCodes
     */
	var	$validatorErrorCodes  =   array(
		'C'	=>	array(
			1	=>	'Please enter the following information',
			2	=>	'Value is not a string.',
			3	=>	'The value is shorter than the minimum length of [MINLENGTH].',
			4	=>	'The value is longer than the maximum length of [MAXLENGTH].',
			5	=>	'Markup tags are not allowed',
			6	=>	'The value contains some tags that are not allowed - only \'[ALLOWEDTAGS]\' are allowed here',
			7	=>	'The value contains some tags that are not allowed - \'[DENIEDTAGS]\' are prohibited'
		),
		'de' =>	array(
			1	=>	'Pflichtfeld. Bitte vervollstŠndigen Sie Ihre Angabe.',
			2	=>	'Wert ist keine Zeichenkette.',
			3	=>	'Eingabe zu kurz, bitte geben Sie mindestens [MINLENGTH] Zeichen ein.',
			4	=>	'Eingabe zu lang, bitte geben Sie maximal [MAXLENGTH] Zeichen ein.',
			5	=>	'Tags sind nicht erlaubt.',
			6	=>	'Der Text enthŠlt unerlaubte Tags. Es sind nur die Tags: "[ALLOWEDTAGS]" erlaubt.',
			7	=>	'Der Text enthŠlt unerlaubte Tags. Die Tags: "[DENIEDTAGS]" sind verboten'
		),
		'fr' =>	array(
			1	=>	'Ce champ est obligatoire.',
			2	=>	'Pas une cha”ne de caractres valide.',
			3	=>	'Valeur trop courte. Longueur minimum: [MINLENGTH] caractres.',
			4	=>	'Valeur trop longue. Longueur maximum: [MAXLENGTH] caractres.',
			5	=>	'Les balises ne sont pas autorisŽes.',
			6	=>	'Le texte contient des balises non autorisŽes - les balises autorisŽes sont \'[ALLOWEDTAGS]\'.',
			7	=>	'Le texte contient des balises non autorisŽes - les balises autorisŽes sont \'[ALLOWEDTAGS]\'.'
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
		if( $this->attributes['display'] == 'no' ) {
			return $this->createDisplaylessTag( $value );
		}
		
		if( $this->attributes['edit'] == 'no' ) {
			$this->attributes['disabled']	=	'yes';
		}
		
		return $this->createTag( 'textarea', 'full', $this->getAttributesFor( $this->getFormat() ), $value );
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
		$tag = $this->createDisplaylessTag( $value );
		
		if( $this->attributes['display'] == 'no' ) {
			return $tag;
		}

		return	$value.$tag;		
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
		$required	=	false;
		$empty		=	false;
		
		// store the required flag for easy access
		if( isset( $this->attributes['required'] ) && $this->attributes['required'] == 'yes' ) {
			$required	=	true;
        }
		
		if( strlen( $value ) == 0 ) {
			$empty	=	true;
        }
			
		if( $empty && $required ) {
			$this->addValidationError( 1 );
			return false;
		}
			
		if( $empty && !$required ) {
			return true;
        }
			
		// is it a string?
		if( !is_string( $value ) ) {
			$this->addValidationError( 2 );
			return false;
		}
		
		// check for allowed tags
		if( $this->attributes['allowedtags'] && $this->attributes['allowedtags'] != '*' ) {
            $allowed	=	explode( ',', $this->attributes['allowedtags'] );
            for( $i = 0; $i < count( $allowed ); ++$i )  {
                $allowed[$i]	=	'<' . $allowed[$i] . '>';
            }
            $allowed	=	implode( '', $allowed );
            $newValue	=	strip_tags( $value, $allowed );
            if( strlen( $newValue ) != strlen( $value ) ) {
                $this->addValidationError( 6, array( 'allowedtags' => $this->attributes['allowedtags'] ) );
                return false;
            }
		}

        // check for denied tags
		if( $this->attributes['deniedtags'] ) {
        
        	// must prohibit any tag
        	if( $this->attributes['deniedtags'] == '*' )  {
                $newValue	=	strip_tags( $value );
                if( strlen( $newValue ) != strlen( $value ) ) {
                    $this->addValidationError( 5 );
                    return false;
                }
            }
            else {
                $denied		=	explode( ',', $this->attributes['deniedtags'] );
                $pattern	=	'+\<(' . implode( '|', $denied ) . ')([^\<]*)\>+i';
                if( preg_match( $pattern, $value ) ) {
                    $this->addValidationError( 7, array( 'deniedtags' => $this->attributes['deniedtags'] ) );
                    return false;
                }
            }
            
        }
        
        
		// minlength
		if( isset( $this->attributes['minlength'] ) && strlen( $value ) < $this->attributes['minlength'] ) {
			$this->addValidationError( 3, array( 'minlength' => $this->attributes['minlength'] ) );
			return false;
		}
		
		// maxlength
		if( isset( $this->attributes['maxlength'] ) && strlen( $value ) > $this->attributes['maxlength'] ) {
			$this->addValidationError( 4, array( 'maxlength' => $this->attributes['maxlength'] ) );
			return false;
		}
		return true;
	}
}

?>
