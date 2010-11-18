<?php
/**
 * patForms Date element
 *
 * Powerful date element with a very flexible date parser that enables
 * very intuitive usage of date input uses.
 *
 * $Id: Date.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 */

/**
 * Stores the path to the element's folder - needed to include the subclasses
 */
 define( 'PATFORMS_ELEMENT_DATE_INSTALL_DIR', dirname( __FILE__ ) );

/**
 * Notice: could not use the date set as default for the element, as it does not match
 *         the specified date format string.
 */
 define( 'PATFORMS_ELEMENT_DATE_NOTICE_DATE_PARSE_ERROR', 'patForms:Element:Date:01' );

/**
 * Notice: the date string ended unexpectedly - it is too short according to the format.
 */
 define( 'PATFORMS_ELEMENT_DATE_NOTICE_DATE_UNEXPECTED_END', 'patForms:Element:Date:02' );

/**
 * Error: the specified date string is not a valid strtotime date string.
 */
 define( 'PATFORMS_ELEMENT_DATE_ERROR_INVALID_DATE_DEFINITION', 'patForms:Element:Date:04' );

/**
 * Error: the PEAR_Calendar class is not installed
 */
 define( 'PATFORMS_ELEMENT_DATE_WARNING_CALENDAR_NOT_INSTALLED', 'patForms:Element:Date:05' );
 
/**
 * Error: the specified date string is not a valid strtotime date string.
 */
 define( 'PATFORMS_ELEMENT_DATE_ERROR_PEAR_DATE_NEEDED', 'patForms:Element:Date:05' );
 @include_once 'Date.php';
 if( !class_exists( 'Date' ) ) {
	patErrorManager::raiseError(
		PATFORMS_ELEMENT_DATE_ERROR_PEAR_DATE_NEEDED,
		'Date class not found',
		'The patForms Date element needs the PEAR::Date class to function, but it could not be found.'
	);
 }

/**
 * Subelement class file, always needed.
 */
 require_once PATFORMS_ELEMENT_DATE_INSTALL_DIR.'/Date/Element.php';

/**
 * patForms Date element
 *
 * Powerful date element with a very flexible date parser that enables
 * very intuitive usage of date input uses.
 *
 * $Id: Date.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 * @license		LGPL
 */
class patForms_Element_Date extends patForms_Element
{
   /**
	* Stores the name of the element - this is used mainly by the patForms
	* error management and should be set in every element class.
	* @access	public
	*/
	var $elementName	=	'Date';

   /**
	* set here which attributes you want to include in the element if you want to use
	* the {@link patForms_Element::convertDefinition2Attributes()} method to automatically
	* convert the values from your element definition into element attributes.
	*
	* @access	protected
	* @see		patForms_Element::convertDefinition2Attribute()
	*/
	var	$attributeDefinition	=	array(

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
		'type' => array(
			'required'		=>	false,
			'format'		=>	'string',
			'default'		=>	'text',
			'outputFormats'	=>	array( 'html' ),
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
		'max' => array(
			'required'		=>	false,
			'format'		=>	'datetime',
			'outputFormats'	=>	array(),
		),
		'min' => array(
			'required'		=>	false,
			'format'		=>	'datetime',
			'outputFormats'	=>	array(),
		),
		'format' => array(
			'required'		=>	false,
			'format'		=>	'datetime',
			'outputFormats'	=>	array(),
		),
		'dateformat' => array(
			'required'		=>	false,
			'format'		=>	'string',
			'default'		=>	'Y.m.d H:i:s',
			'outputFormats'	=>	array(),
		),
		'presets' => array(
			'required'		=>	false,
			'format'		=>	'string',
			'default'		=>	'yes',
			'outputFormats'	=>	array(),
		),
		'disabled' => array(
			'required'		=>	false,
			'format'		=>	'string',
			'default'		=>	'no',
			'outputFormats'	=>	array( 'html' ),
		),
		'returnformat' => array(
			'required'		=>	false,
			'format'		=>	'string',
			'default'		=>	'datestring',
			'outputFormats'	=>	array(),
		),
	);

   /**
    * define error codes and messages for each form element
    *
	* @access private
	* @var	array	$validatorErrorCodes
	*/
	var	$validatorErrorCodes  =   array(
		'C'	=>	array(
			1	=>	'Please enter the following information',
			2	=>	'Value must be after \'[MINDATE]\'!',
			3	=>	'Value must be before \'[MAXDATE]\'!',
			4	=>	'Incorrect date format',
			5	=>	'This date does not exist'

		),
		'de' =>	array(
			1	=>	'Pflichtfeld. Bitte vervollstŠndigen Sie Ihre Angabe.',
			2	=>	'Der Wert muss nach \'[MINDATE]\' sein.',
			3	=>	'Der Wert muss vor \'[MAXDATE]\' sein.',
			4	=>	'Falsches Datumsformat',
			5	=>	'Dieses Datum existiert nicht'
		),
		'fr' =>	array(
			1	=>	'Ce champ est obligatoire.',
			2	=>	'La date doit tre aprs le \'[MINDATE]\'.',
			3	=>	'La date doit tre avant le \'[MAXDATE]\'.',
			4	=>	'Format de date incorrect',
			5	=>	'Cette date n\'existe pas'
		),
	);

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
	* Stores a subelement counter used for the automatic ID generation for each of
	* the date element's sub elements.
	*
	* @access	private
	* @var		int
	*/
	var $elCount = 0;

   /**
	* Stores an index of all date tokens the date element supports, and the
	* corresponding subelement that handles the token.
	*
	* @access	private
	* @var		array
	* @see		$tokens
	*/
	var $tokensIndex = array(
		'Y'	=>	'Year',
		'y'	=>	'Year',
		'd'	=>	'Day',
		'j'	=>	'Day',
		'F' =>	'Month',
		'm'	=>	'Month',
		'M'	=>	'Month',
		'n'	=>	'Month',
		'i'	=>	'Minute',
		'a'	=>	'Meridiem',
		'A'	=>	'Meridiem',
		'g'	=>	'Hour',
		'G'	=>	'Hour',
		'h'	=>	'Hour',
		'H'	=>	'Hour',
		's'	=>	'Second',
	);

   /**
	* Stores a list of date tokens for which the min and max attributes
	* have to be set when in 'presets' mode.
	*
	* @access	private
	* @var		array
	*/
	var $minmaxTokens = array(
		'Y',
		'y'
	);

   /**
	* Similar to the {@link $tokensIndex} property, only without the subelements
	* information - only the tokens, as an indexed array so it is easier to check
	* whether a token is available.
	*
	* @access	private
	* @var		array
	*/
	var $tokens = array();

   /**
	* Stores a list of all tokens the current date format string uses. Needed by some
	* subelements like the Meridiem subelement to check whether the related tokens are
	* present.
	*
	* @access	private
	* @var		array
	* @see		tokenUsed()
	*/
	var $usedTokens = array();

   /**
	* Stores a collection of all the elements the current date format uses. Built on
	* startup by the _init() method.
	*
	* @access	private
	* @var		array
	* @see		_init()
	*/
	var $dateElements = array();

   /**
	* Stores all date element objects needed for the current date format string. Created
	* automatically on startup by the _init() method.
	*
	* @access	private
	* @var		array
	* @see		_init()
	*/
	var $elements = array();

   /**
	* Stores a list of characters contained in the date format string that will be transformed
	* when serializing to HTML to ensure the output stays correct.
	*
	* @access	private
	* @var		array
	*/
	var $transformTable = array(
		' '	=>	'&#160;'
	);

   /**
	* Stores a list of attributes that all subelements will inherit from the date element
	*
	* @access	private
	* @var		array
	*/
	var $inheritableAttributes = array(
		'edit',
		'display',
		'required',
		'style',
		'class'
	);

   /**
	* Stores an index of all elements for easy element name handling
	*
	* @access	private
	* @var		array
	*/
	var $elementsIndex = array();

   /**
	* Stores the current date object (the value of the element)
	*
	* @access	private
	* @var		object
	*/
	var $date = null;

   /**
	* Stores the default date object
	*
	* @access	private
	* @var		object
	*/
	var $defaultDate = null;

   /**
	* Stores the max date object
	*
	* @access	private
	* @var		object
	*/
	var $maxDate = null;

   /**
	* Stores the min date object
	*
	* @access	private
	* @var		object
	*/
	var $minDate = null;

   /**
	* Stores attributes for which there are special setter methods
	* which need to be called. This is mainly for date attributes
	* that need to be converted to date objects internally.
	*
	* @access	private
	* @var		array
	*/
	var $setterAttribs = array(
		'default',
		'max',
		'min'
	);

   /**
	* Runs all needed tasks that the date element needs to run - this
	* includes parsing the date format string and generating the date
	* subelements collection. Also sets any default values and max/min
	* directives as required.
	*
	* @access	private
	* @return	bool	$success	Always returns true. Errors occurring on parsing the default date are ignored and the default date ignored as well in that case.
	* @see		$dateElements
	* @see		$elements
	*/
	function _init()
	{
		parent::_init();

		// presets mode? check the attribute collection
		if( $this->attributes['presets'] == 'yes' ) {
			$this->checkMinMaxAttribs();
		}

		$this->tokens = array_keys( $this->tokensIndex );

		// now parse the format string to create the
		// elements collection. This fills the dateElements
		// property as well as the elements property.
		$cnt = strlen( $this->attributes["dateformat"] );
		for( $i=0; $i < $cnt; $i++ )
		{
			$char = $this->attributes["dateformat"][$i];

			if( in_array( $char, $this->tokens ) ){

				$elementID = $this->tokensIndex[$char];

				$this->elements[$elementID] = $this->createElement( $elementID );
				$this->elements[$elementID]->setToken( $char );

				$element = array(
					'type'		=>	'token',
					'elementID'	=>	$elementID,
				);

				if( !in_array( $char, $this->usedTokens ) ) {
					array_push( $this->usedTokens, $char );
				}
			} else {
				$element = array(
					'type' 		=> 'cdata',
					'content'	=>	$char
				);
			}

			array_push( $this->dateElements, $element );
		}

		$this->elementsIndex = array_keys( $this->elements );

		// inherit any inheritable attributes
		foreach( $this->inheritableAttributes as $attribute ) {
			if( !isset( $this->attributes[$attribute] ) ) {
				continue;
			}
			$this->inheritAttribute( $attribute, $this->attributes[$attribute] );
		}

		// special attributes which have their own setter methods
		foreach( $this->setterAttribs as $attributeName ) {
			if( isset( $this->attributes[$attributeName] ) ) {
				$setterMethod = 'set'.ucfirst( $attributeName );
				$this->$setterMethod( $this->attributes[$attributeName] );
			}
		}

		// submitted state
		foreach( $this->elementsIndex as $elementID ) {
			$this->elements[$elementID]->setSubmitted( $this->submitted );
		}

		foreach( $this->elementsIndex as $elementID ) {
			$this->elements[$elementID]->init();
		}

		return true;
	}

   /**
	* Wrapper for the main element function, with added functionality to
	* inherit the submitted state to all subelements.
	*
	* @access	public
	* @param	bool	$state	True if it has been submitted, false otherwise (default).
	*/
	function setSubmitted( $state )
	{
		$this->submitted = $state;

		foreach( $this->elementsIndex as $elementID ) {
			$this->elements[$elementID]->setSubmitted( $this->submitted );
		}
	}

   /**
	* set the element's namespace
	*
	* @static
	* @access	public
	* @param	string		namespace
	* @return	null
	*/
	function setNamespace($namespace)
	{
		foreach($this->elementsIndex as $elementID) {
			$this->elements[$elementID]->setNamespace($namespace);
    	}
	}

   /**
	* Some date tokens require the min and max attributes to be set when
	* in presets mode, as these dates are used to create the span with
	* which the date selectors are filled. This method checks if all is
	* set.
	*
	* @access	private
	* @return	mixed	$success	True if OK, a patError object otherwise
	*/
	function checkMinMaxAttribs()
	{
		$found = false;
		foreach( $this->minmaxTokens as $minmaxToken ) {
			if( strstr( $this->attributes['dateformat'], $minmaxToken ) ) {
				$found = true;
				break;
			}
		}

		if( !$found ) {
			return true;
		}

		if( !isset( $this->attributes['min'] ) ) {
			if( !is_null( $this->defaultDate ) ) {
				$minDate = new Date( $this->defaultDate );
			} else {
				$minDate = new Date();
			}

			$this->attributes['min'] = $minDate->getDate();
		}

		if( !isset( $this->attributes['max'] ) ) {
			if( !is_null( $this->defaultDate ) ) {
				$maxDate = new Date( $this->defaultDate );
			} else {
				$maxDate = new Date();
			}

			// add 5 years
			$maxDate->addSeconds( 157680000 );

			$this->attributes['max'] = $maxDate->getDate();
		}

		return true;
	}

	function setDefault( $date )
	{
		$this->defaultDate = $this->getDate( $date );
		if( patErrorManager::isError( $this->defaultDate ) ) {
			return $this->defaultDate;
		}

		foreach( $this->elementsIndex as $elementID ) {
			$this->elements[$elementID]->setDefault( $this->defaultDate );
		}
	}

	function setMax( $date )
	{
		$this->maxDate = $this->getDate( $date );
		if( patErrorManager::isError( $this->maxDate ) ) {
			return $this->maxDate;
		}

		foreach( $this->elementsIndex as $elementID ) {
			$this->elements[$elementID]->setMax( $this->maxDate );
		}
	}

	function setMin( $date )
	{
		$this->minDate = $this->getDate( $date );
		if( patErrorManager::isError( $this->minDate ) ) {
			return $this->minDate;
		}

		foreach( $this->elementsIndex as $elementID ) {
			$this->elements[$elementID]->setMin( $this->minDate );
		}
	}

   /**
	* Converts a strtotime-compatible date string that is used to define
	* the default date, as well as the max/min dates into a date string
	* according to the given format so the internal date parser can handle
	* it.
	*
	* @access	private
	* @param	string	$dateElement	A date string/object
	* @return	mixed	$date	The converted, internal parsing engine compatible date string or a patError object if failed.
	* @todo		Under windows, the strtotime function does not return the time, only the date in its timestamp. Check a solution for this problem.
	*/
	function &getDate( $dateElement )
	{
		if( !is_object( $dateElement ) ) {
			$date = new Date( $dateElement );
			if( $date->getYear() == 0 ) {
				$stamp = strtotime( $dateElement );
				if( $stamp == -1 ) {
					return patErrorManager::raiseError(
						PATFORMS_ELEMENT_DATE_ERROR_INVALID_DATE_DEFINITION,
						'Invalid date',
						'['.$dateElement.'] seems not to be a valid date argument for the strtotime function. Please check the syntax.'
					);
				}
				
				$date = new Date( $stamp );
			}
		}

		// if there is no year, the date object could not convert the
		// date string to a valid date.
		if( $date->getYear() == 0 ) {
			return patErrorManager::raiseError(
				PATFORMS_ELEMENT_DATE_ERROR_INVALID_DATE_DEFINITION,
				'Invalid date',
				'['.$dateElement.'] seems not to be a valid date. Please check your date syntax.'
			);
		}
		
		return $date;
	}
	
   /**
	* Sets the element's value. In the date element's case, this means parsing the
	* date string or timestamp, and set the according values for all subelements.
	*
	* @access	public
	* @param	string	$value	The date to set. Has to be a timestamp or strtotime compatible string
	*/
	function setValue( $value )
	{
		$this->date = $this->getDate( $value );
		if( patErrorManager::isError( $this->date ) ) {
			return $this->date;
		}

		foreach( $this->elementsIndex as $elementID ) {
			$this->elements[$elementID]->setValue( $this->date );
		}
	}

   /**
	* Checks whether a token has been used in the current date format string.
	* Used by some subelements to check whether the related tokens have been set
	* or not.
	*
	* @access	private
	* @param	string	$token	The token to check
	* @return	bool	$used	True if used, false otherwise.
	* @see		$usedTokens
	*/
	function tokenUsed( $token )
	{
		if( in_array( $token, $this->usedTokens ) ) {
			return true;
		}

		return false;
	}

   /**
	* Creates a date subelement from the corresponding class file, instantiates and
	* configures it, and returns it.
	*
	* @access	private
	* @param	string	$role		The role of the element, i.e. its name
	* @return	object	$element	The requested element object
	*/
	function &createElement( $role )
	{
		$file = PATFORMS_ELEMENT_DATE_INSTALL_DIR.'/Date/Element/'.$role.'.php';
		$elClassName = 'patForms_Element_Date_Element_'.$role;

		$this->elCount++;

		include_once $file;

		$el = new $elClassName();
		$el->setID( $this->elCount );
		$el->setParentName( $this->attributes['name'] );
		$el->setParentID( $this->attributes['id'] );
		$el->setParent( $this );
		$el->setLocale( $this->locale );

		if( $this->attributes['presets'] == 'yes' ) {
			$el->setMode( 'presets' );
		}

		return $el;
	}

   /**
	* element creation method for the 'HTML' format in the 'default' form mode.
	*
	* @access	public
	* @param	mixed	value of the element
	* @return	mixed	$element	The element, or false if failed.
	*/
	function serializeHtmlDefault( $value )
	{
		// handle display attribute
		if( $this->attributes['display'] == 'no' )
		{
			return $this->createDisplaylessTag( $value );
		}

		$content	=	'<table cellpadding="0" cellspacing="0" border="0">'
					.	'	<tr>';

		foreach( $this->dateElements as $row => $element )
		{
			$content .= '<td>';

			switch( $element['type'] ) {
				case 'token':
					$content .= $this->elements[$element['elementID']]->serialize();
					break;

				default:
					if( isset( $this->transformTable[$element['content']] ) ) {
						$content .= $this->transformTable[$element['content']];
					} else {
						$content .= $element['content'];
					}
					break;
			}

			$content .= '</td>';
		}

		$content	.=	'	</tr>'
					.	'</table>';

		return $content;
	}

   /**
	* Special implementation of the common method to gather the subelement's
	* displayless tags and use them as displayless content.
	*
	* @access	private
	* @param	mixed	$value	Unused - the value of the element
	* @return	mixed	$content	The element content, or a patError object if failed.
	*/
	function createDisplaylessTag( $value )
	{
		$content = '';

		$this->inheritAttribute( 'display', 'no' );

		foreach( $this->elements as $elementID => $element ) {
			$serialized = $element->serialize();
			if( patErrorManager::isError( $serialized ) ) {
				return $serialized;
			}

			$content .= $serialized;
		}

		return $content;
	}

   /**
	* Retrieves a format string for use with the date() function,
	* in the ISO 8601 format. Use this if you are too lazy to
	* remember it by yourself.
	*
	* @access	public
	* @return	string	$formatString	The format string
	*/
	function getISOFormat()
	{
		return 'Y-m-d H:i:s';
	}

   /**
	* Makes an attribute of the date element be inherited by the subelements.
	*
	* @access	public
	* @param	string	$attribute	The name of the attribute
	* @param	mixed	$value		The value of the attribute
	*/
	function inheritAttribute( $attribute, $value )
	{
		if( !in_array( $attribute, $this->inheritableAttributes ) ) {
			return true;
		}

		foreach( $this->elementsIndex as $elementID ) {
			$this->elements[$elementID]->inheritAttribute( $attribute, $value );
		}

		return true;
	}

   /**
	* element creation method for the 'HTML' format in the 'readonly' form mode.
	* Very simple; just returns the stored element value.
	*
	* @access	public
	* @param	mixed	value of the element
	* @return	string	$value	The element's value
	* @todo		check wether the call to getAttributesFor() is needed
	*/
	function serializeHtmlReadonly( $value )
	{
		$tag = $this->createDisplaylessTag( $value );

		// handle display attribute
		if( $this->attributes['display'] == 'no' )
		{
			return $tag;
		}

		return $value.$tag;
	}

   /**
	* Retrieve a dte string according to the specified dateformat. Use
	* this if you need the selected date in that format, which is
	* otherwise solely used for layout.
	*
	* @access	public
	* @return	string	$formattedDate	The formatted date
	*/
	function getFormattedDate()
	{
		// resolve the current date
		$this->resolveValue();

		return date( $this->attributes['dateformat'], $this->date->getTime() );
	}

   /**
	* resolves the scope the value of the element may be stored in, and returns it.
	*
	* @access	protected
	* @see		getValue()
	* @see		value
	* @todo		parse element name, if it uses the array syntax
	*/
	function resolveValue()
	{
		$returnformat = 'isodate';
		if( $this->attributes['returnformat'] != $returnformat ) {
			$returnformat = $this->attributes['returnformat'];
		}

		// resolve date object to use
		if( is_null( $this->date ) ) {
			if( !is_null( $this->defaultDate ) ) {
				$this->date = $this->defaultDate;
			} else {
				$this->date = new Date();
			}
		}

		$valid = true;

		// now set the needed parts of the date element
		foreach( $this->elementsIndex as $elementID ) {
			$methodName = $this->elements[$elementID]->setterMethod();
			$value = $this->elements[$elementID]->getValue();

			if( is_null( $value ) ) {
				$valid = false;
				continue;
			}

			if ($methodName) {
				$this->date->$methodName( $value );
			}
		}

		if( !$valid ) {
			$this->value = null;
			return true;
		}

		switch( $returnformat ) {
			case 'timestamp':
				$this->value = $this->date->getTime();
			break;

			case 'isodate':
			default:
				$this->value = $this->date->getDate();
				break;
		}

		$this->value = $this->_applyFilters( $this->value, 'in', PATFORMS_FILTER_TYPE_PHP );

		return true;
	}

   /**
	* validates the element.
	*
	* @access	public
	* @param	mixed	value of the element
	* @return	bool	$isValid	True if element could be validated, false otherwise.
	* @todo		The required check should go through each subelement and generate an error message according to the subelement(s) that have errors
	*/
	function validateElement( $value )
	{
		$required	=	false;

		// store the required flag for easy access
		if( $this->attributes['required'] == "yes" ) {
			$required = true;
		}

		// nothing specified and element is not required, we're done.
		if( !$required && is_null( $value ) ) {
			return true;
		}
		
		// nothing specified and element is required, we need some data.
		if( $required && is_null( $value ) )
		{
			$this->addValidationError( 1 );
			return false;
		}

		foreach( $this->elementsIndex as $elementID ) {
			if( !$this->elements[$elementID]->validate() ) {
				$this->addValidationError( 4 );
				return false;
			}
		}

		if( !is_null( $this->minDate ) && $this->date->before( $this->minDate ) ) {
			$this->addValidationError( 2, array( 'mindate' => $this->minDate->getDate() ) );
		}

		if( !is_null( $this->maxDate ) && $this->date->after( $this->maxDate ) ) {
			$this->addValidationError( 3, array( 'maxdate' => $this->maxDate->getDate() ) );
		}
		
		$date = $this->getDate( $value );
		$adjusted = date( 'Y-m-d H:i:s', mktime( $date->getHour(), $date->getMinute(), $date->getSecond(), $date->getMonth(), $date->getDay(), $date->getYear() ) );
		if( $adjusted != $date->getDate() ) {
			$this->addValidationError( 5 );
			return false;
		}

		// validate input
		return true;
	}

   /**
	* Wrapper for the normal element setAttribute method, which handles
	* inheriting the attributes to all its children
	*
	* @access	public
	* @param	string	$attributeName	The name of the attribute to add
	* @param	string	$attributeValue	The value of the attribute
	* @return	mixed	$success		True on success, a patError object otherwise
	*/
	function setAttribute( $attributeName, $attributeValue )
	{
		// set the attribute normally
		$success = parent::setAttribute( $attributeName, $attributeValue );
		if( patErrorManager::isError( $success ) ) {
			return $success;
		}

		// special attributes that have their own setter method
		if( in_array( $attributeName, $this->setterAttribs ) ) {
			$setterMethod = 'set'.ucfirst( $attributeName );
			$this->$setterMethod( $attributeValue );
		}

		return $this->inheritAttribute( $attributeName, $attributeValue );
	}

}
?>