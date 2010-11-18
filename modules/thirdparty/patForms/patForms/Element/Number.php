<?php
/**
 * simple test patForms element that builds and validates numbers input fields.
 * 
 * $Id: Number.php,v 1.2 2006/06/21 02:56:09 eric Exp $
 *
 * @package		patForms
 * @subpackage	Element
 */

 define( 'PATFORMS_ELEMENT_NUMBER_WARNING_WRONG_FORMAT_STRING', 'patForms:Element:Number:01' );
 
/**
 * simple textfield patForms element that builds and validates text input fields.
 * 
 * $Id: Number.php,v 1.2 2006/06/21 02:56:09 eric Exp $
 *
 * @package		patForms
 * @subpackage	Element
 * @author		gERD Schaufelberger <gerd@php-tools.net>
 * @author		Sebastian Mordziol <argh@php-tools.net>
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @todo		Review getNumberFormat() -> poor "quoting"!
 * @license		LGPL
 */
class patForms_Element_Number extends patForms_Element
{
   /**
	* Stores the name of the element - this is used mainly by the patForms
	* error management and should be set in every element class.
	* @access	public
	*/
	var $elementName = 'Number';

   /**
	* the type of the element - set this to the type of element you are creating
	* if you want to use the {@link patForms_Element::element2html()} method to 
	* create the final HTML tag for your element.
	*
	* @access	public
	* @see		patForms_Element::element2html()
	*/
	var $elementType = array(
		'html'	=>	'input'
	);
	
    /**
     *	output definition for number
     *
     *  @access private
     *  @var	array	$numberformat
     */
	var	$numberformat = false;

   /**
	* set here which attributes you want to include in the element if you want to use
	* the {@link patForms_Element::convertDefinition2Attributes()} method to automatically
	* convert the values from your element definition into element attributes.
	*
	* @access	protected
	* @see		patForms_Element::convertDefinition2Attribute()
	*/
	var	$attributeDefinition	=	array(	
			
			'id'			=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array( 'html' ),
									),
			'name'			=>	array(	'required'		=>	true,
										'format'		=>	'string',
										'outputFormats'	=>	array( 'html' ),
										'modifiers'		=>	array( 'insertSpecials' => array() ),
									),
			'type'			=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'default'		=>	'text',
										'outputFormats'	=>	array( 'html' ),
									),
			'title'			=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array( 'html' ),
										'modifiers'		=>	array( 'insertSpecials' => array() ),
									),
			'description'	=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array(),
										'modifiers'		=>	array( 'insertSpecials' => array() ),
									),
			'default'		=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array(),
									),
			'label'			=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array(),
									),
			'display'		=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'default'		=>	'yes',
										'outputFormats'	=>	array(),
									),
			'edit'			=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'default'		=>	'yes',
										'outputFormats'	=>	array(),
									),
			'disabled'		=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'default'		=>	'no',
										'outputFormats'	=>	array( 'html' ),
									),
			'required'		=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'default'		=>	'yes',
										'outputFormats'	=>	array(),
									),
			'value'			=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array( 'html' ),
									),
			'style'			=>	array(	'required'		=>	false,
										'outputFormats'	=>	array( 'html' ),
										'format'		=>	'string',
									),
			'class'			=>	array(	'required'		=>	false,
										'outputFormats'	=>	array( 'html' ),
										'format'		=>	'string',
									),
			'onchange'		=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array( 'html' ),
										'modifiers'		=>	array( 'insertSpecials' => array() ),
									),
			'onclick'		=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array( 'html' ),
										'modifiers'		=>	array( 'insertSpecials' => array() ),
									),
			'onfocus'		=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array( 'html' ),
										'modifiers'		=>	array( 'insertSpecials' => array() ),
									),
			'onmouseover'	=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array( 'html' ),
										'modifiers'		=>	array( 'insertSpecials' => array() ),
									),
			'onmouseout'	=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array( 'html' ),
										'modifiers'		=>	array( 'insertSpecials' => array() ),
									),
			'onblur'		=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array( 'html' ),
										'modifiers'		=>	array( 'insertSpecials' => array() ),
									),
			'accesskey'		=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array( 'html' ),
									),
			'position'		=>	array(	'required'		=>	false,
										'format'		=>	'int',
										'outputFormats'	=>	array(),
									),
			'tabindex'		=>	array(	'required'		=>	false,
										'format'		=>	'int',
										'outputFormats'	=>	array( 'html' ),
									),
			'max'			=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array(),
									),
			'min'			=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array(),
									),
			'format'		=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'setter'        =>  'setValueFormat',
										'outputFormats'	=>	array(),
									),
			'numberformat'	=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'default'		=>	'0||',
										'outputFormats'	=>	array(),
									),
			'formatseparator'=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'default'		=>	'|',
										'outputFormats'	=>	array(),
									),
			'size'			=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array( 'html' ),
									),
			'maxlength'		=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array( 'html' ),
									),
		);

    /**
     *	define error codes an messages for each form element
     *
     *  @access private
     *  @var	array	$validatorErrorCodes
     */
	var	$validatorErrorCodes  =   array(
		'C'	=>	array(
			1	=>	'Please enter the following information',
			2	=>	'Value is not a number.',
			3	=>	'Value is smaller than the minimum of [MIN].',
			4	=>	'Value is higher than the maximum of [MAX].',
			5	=>	'The value does not match the required input format.',
		),
		'de' =>	array(
			1	=>	'Pflichtfeld. Bitte vervollständigen Sie Ihre Angabe.',
			2	=>	'Wert ist keine Zahl.',
			3	=>	'Wert zu klein, kleinster erlaubter Wert ist [MIN].',
			4	=>	'Wert zu groß, größter erlaubter Wert ist [MAX].',
			5	=>	'Der angegebene Wert entspricht nicht dem gewünschten Eingabeformat.',
		),
		'fr' =>	array(
			1	=>	'Ce champ est obligatoire.',
			2	=>	'Pas un nombre.',
			3	=>	'Valeur trop petite. Valeur minimum admise: [MIN].',
			4	=>	'Valeur trop grande. Valuer maximum admise: [MAX].',
			5	=>	'La valeur ne correspond pas au format souhaité.',
		)
	);

	/**
	 * Extract the number format from the numberformat-attribute
	 *
	 * This method allows to define the numberformat as a string with
	 * comma seperated values of decimals, decimal-point and thousands seperator.
	 * For using a "," (comma) as separator, it must be quoted: "%,".
	 * 
	 * The returned array contains the three format parameters for number_format() in
	 * the same order as the function expects.
	 * 
	 * Examples:
	 * <ul> 
	 *   <li>"0||" simple integer (default), ex. 1234</li>
	 *   <li>"2|,|." german/french format, ex. "1.234,56"</li>
	 *   <li>"2|.| " english format, ex. "1 234.56" </li>
	 * </ul>
	 *
	 *	@access	private
	 *	@return array $format the extracted number format
	 *	@see number_format()
	 */
    function getNumberFormat()
    {
		if ($this->numberformat) {
			return $this->numberformat;
		}
		
		$format = explode( $this->attributes['formatseparator'], $this->attributes['numberformat'] );
		
		if (count( $format ) !== 3) {
			patErrorManager::raiseWarning(
				PATFORMS_ELEMENT_NUMBER_WARNING_WRONG_FORMAT_STRING,
				'Incorrect number format string, using default',
				'The number format string has to have three parts, e.g. "0|.| " for the english number format -> "1 234.56", your format string has '.count( $format ).'. Now using the default format, "'.$this->attributeDefinition['numberformat']['default'].'"'
			);
			$format = explode( $this->attributes['formatseparator'], $this->attributeDefinition['numberformat']['default'] );
		}
		
		$this->numberformat = $format;
		return $format;
    }
    
   /**
	* Manages the value that will be used
	* 
	* @access	private
	* @param	mixed	$value	The current raw value
	* @return	mixed	$value	The value that should be used
	*/
    function _manageValue( $value )
    {
		// if the element has been validated we know the
		// value is really a number, we can format it according
		// to the given format.
		if ($this->valid && $value !== '') {
            $value = $this->formatNumber($value);
		}
		// and return to sender...
		return $value;
    }
		
   /**
	* element creation method for the 'HTML' format in the 'default' form mode.
	*
	* @access	public
	* @param	mixed	value of the element
	* @return	mixed	$element	The element, or false if failed.
	*/
	function serializeHtmlDefault($value)
	{
		if ($this->attributes['edit'] == 'no') {
			$this->attributes['disabled'] = 'yes';
		}
		$value = $this->_manageValue($value);
		$this->attributes['value'] = $value;

		if ($this->attributes['display'] == 'no') {
			return $this->createDisplaylessTag( $value );
		}

		return $this->toHtml();
	}
	
   /**
	* Formats a number according to the number format set for the element
	* 
	* @access	private
	* @param	int		$number	The number to format
	* @return	string	$number	The formatted number
	* @see		getNumberFormat()
	*/
	function formatNumber( $number )
	{
		$format = $this->getNumberFormat();

		return number_format( $number, $format[0], $format[1], $format[2] );
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
		$this->getAttributesFor($this->getFormat());

		$value = $this->_manageValue($value);
		$tag = $this->createDisplaylessTag($value);
		
		if ($this->attributes['display'] == 'no') {
			return $tag;
		}
		return $value.$tag;
	}

   /**
	* validates the element.
	*
	* @access	public
	* @param	mixed	value of the element
	* @return	bool	$isValid	True if element could be validated, false otherwise.
	*/
	function validateElement($value)
	{
		// required & empty
		if ($this->attributes['required'] == 'yes' && strlen( $value ) == 0) {
			$this->addValidationError(1);
			return false;
		}
		
		$value = $this->inputValueToFloat($value);
		
		// only numeric values are accepted
		if (!is_numeric($value)) {
			
			if ($this->attributes['required'] != 'yes' && strlen($value) == 0) {
				
			} else {
			
				$this->addValidationError(2);
				return false;
			}
		}

		// min
		if (isset($this->attributes['min'] ) && $value < $this->attributes['min']) {
			$this->addValidationError(3, array( 'min' => $this->attributes['min']));
			return false;
		}
		
		// max
		if (isset($this->attributes['max']) && $value > $this->attributes['max']) {
			$this->addValidationError(4, array('max' => $this->attributes['max']));
			return false;
		}
		
		// format
		if (isset($this->attributes['format']) && !$this->validateFormat($value, $this->attributes['format'])) {
			$this->addValidationError(5);
			return false;
		}
		
		$this->value = $value;
		return true;
	}
	
   /**
	* transforms input value to floating point number
	*
	*
	* @access private
	* @param string $value the value-string
	* @return float $value transformed value
	*/
    function inputValueToFloat($value)
    {
		$format	=	$this->getNumberFormat();
		
		// remove thousand-seperator
		$result	= str_replace($format[2], '', $value);
		
		// replace decimal-point
		$result = str_replace( $format[1], '.', $result );
		
		// now this should be number!
		if (!is_numeric($result)) {
			return $value;
		}		
		
		// cast to float
		return (float) $result;
    }
}
?>
