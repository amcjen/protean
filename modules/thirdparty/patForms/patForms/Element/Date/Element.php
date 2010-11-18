<?php
/**
 * patForms Date subelement main class
 *
 * Each part of a date is a separate object - this class build the
 * base structure of each date part, and which each part extends.
 *
 * $Id: Element.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 */

/**
 * patForms Date subelement main class
 *
 * Each part of a date is a separate object - this class build the
 * base structure of each date part, and which each part extends.
 *
 * $Id: Element.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 */
class patForms_Element_Date_Element
{
   /**
	* Defines the element type that will be used depending on the date
	* element's mode.
	*
	* @access	private
	* @var		array
	*/
	var $elementTypes = array(
		'default'	=>	'String',
		'presets'	=>	'Enum'
	);

   /**
	* Stores the mode of the element
	*
	* @access	private
	* @var		string
	*/
	var $mode = 'default';

   /**
	* Stores the actual token from the date format that will be
	* used as base to generate the according field (e.g. 'Y' for
	* a 4-digit year )
	*
	* @access	private
	* @var		string
	*/
	var $token = null;

   /**
	* Stores each subelement's supported tokens and their configuration
	*
	* @abstract
	* @access	private
	* @var		array
	*/
	var $tokens = array();

   /**
	* Stores a compatibility table of date tokens that will be converted if used
	* to the alternate token specified.
	*
	* @abstract
	* @access	private
	* @var		array
	*/
	var $compatTable = array();

   /**
	* Stores the patForms element object that will be used to
	* reperesent this part in the date format
	*
	* @access	private
	* @var		object
	*/
	var $element = null;

   /**
	* Stores the ID of the parent element, which is always the
	* date element itself.
	*
	* @access	private
	* @var		string
	*/
	var $parentID = null;

   /**
	* Stores this element's ID within the date element - used to
	* generate a unique ID for each of the date element's subelements.
	*
	* @access	private
	* @var		int
	*/
	var $id = null;

   /**
	* Stores the name of the parent element, which is always the date
	* element. Used to generate the names for each of the date element's
	* subelements.
	*
	* @access	private
	* @var		string
	*/
	var $parentName = null;

   /**
	* Stores a reference to the parent object for additional functions
	* that should require it.
	*
	* @access	private
	* @var		object
	*/
	var $parent = null;

   /**
	* Stores the locale from patForms, and which will be used for all date
	* output that is locale-dependent.
	*
	* @access	private
	* @var		string
	*/
	var $locale = 'C';

   /**
	* Stores the attributes collection for the patForms element that is
	* used for this element.
	*
	* @access	private
	* @var		array
	*/
	var $attributes = array();

   /**
	* Stores the default attributes collection for the patForms element that
	* is used for this element. This is split into the modes the element
	* supports, to make sure that the different field types do not hinder
	* each other with conflicting attribute collections.
	*
	* @access	private
	* @var		array
	*/
	var $defaultAttributes = array(
		'default' => array(
			'size'		=>	2,
			'maxlength'	=>	2,
		),
		'presets' => array(
		)
	);

   /**
	* Stores the default date object.
	*
	* @access	private
	* @var		object
	*/
	var $defaultDate = null;

   /**
	* Stores the max date object.
	*
	* @access	private
	* @var		object
	*/
	var $maxDate = null;

   /**
	* Stores the min date object.
	*
	* @access	private
	* @var		object
	*/
	var $minDate = null;

   /**
	* Stores whether the element has been submitted.
	*
	* @access	private
	* @var		bool
	*/
	var $submitted = false;

   /**
	* Stores the element's date object (the element's value)
	*
	* @access	private
	* @var		object
	*/
	var $date = null;

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

	var $initDone = false;

   /**
	* Initializes the element by creating the base attribute collection
	* from the default attributes and the subelement's own implementation
	* of the {@link initAttributes()} method.
	*
	* Called automatically by the date element when serializing or when
	* validating. Creates the patForms element that is used for the input.
	*
	* @access	public
	*/
	function init()
	{
		if( $this->initDone ) {
			return true;
		}

		$elName = $this->parentName.'_'.$this->token;

		// prefill attributes collection for the current mode with the default attributes
		$this->_initAttributes();

		// create the patForms element
		$this->element =& patForms::createElement( $elName, $this->elementTypes[$this->mode], $this->attributes );

		// inherit the submitted state
		$this->element->setSubmitted( $this->submitted );

		$this->initDone = true;

		return true;
	}

   /**
	* Serializes this date subelement by calling the {@link init()} routine
	* and returning the element's serialized contents.
	*
	* @access	public
	* @return	mixed	$content	The content from the element.
	*/
	function serialize()
	{
		// refresh attributes
		$this->_initAttributes();

		// refresh the element's attributes
		$this->element->setAttributes( $this->attributes );

		// and serialize :)
		return $this->element->serialize();
	}

   /**
	* Retrieves the length of this element's value
	*
	* @access	public
	* @return	int	$length	The requested length
	*/
	function getLength()
	{
		return $this->tokens[$this->token]['length'];
	}

   /**
	* Sets the token to use for this date subelement.
	*
	* @access	public
	* @param	string	$token	The token to use
	* @see		$token
	*/
	function setToken( $token )
	{
		$this->token = $token;
	}

   /**
	* Sets a reference to the parent object, which always is the date
	* element. Used by some elements to use some special methods like
	* the {@link patForms_Element_Date::tokenUsed()} method.
	*
	* @access	public
	* @param	object	&$parent	The date element object.
	*/
	function setParent( &$parent )
	{
		$this->parent =& $parent;
	}

   /**
	* Sets the ID of the parent element (always the ID of the date
	* element), set automatically by the date element itself.
	*
	* @access	public
	* @param	string	$id	The ID string
	*/
	function setParentID( $id )
	{
		$this->parentID = $id;
	}

   /**
	* Sets this subelement's ID within the subelements list, so a unique
	* ID can be generated for each element. Set automatically by the date
	* element itself.
	*
	* @access	public
	* @param	int	$id	The ID
	*/
	function setID( $id )
	{
		$this->id = $id;
	}

   /**
	* Sets the name of the parent element, which is always the date element.
	* Used to generate the names of each subelement. Automatically set by
	* the date element itself.
	*
	* @access	public
	* @param	string	$parentName	The name of the date element
	*/
	function setParentName( $parentName )
	{
		$this->parentName = $parentName;
	}

   /**
	* Sets the mode to use for the element - there are two modes which
	* determine how the subelement will be rendered. Set automatically
	* by the date element itself.
	*
	* @access	public
	* @param	string	$mode	The mode to use.
	* @see		$elementTypes
	*/
	function setMode( $mode )
	{
		$this->mode = $mode;
	}

   /**
	* Sets the locale to use for the subelement. This is used to determine
	* which strings to use when language-dependent string have to be used.
	* Set automatically to the global patForms setting by the date element.
	*
	* @access	public
	* @param	string	$locale	The locale
	*/
	function setLocale( $locale )
	{
		$this->locale = $locale;
	}

   /**
	* get the element's namespace
	*
	* @static
	* @access	public
	* @param	string		namespace
	* @return	string
	*/
	function getNamespace()
	{
		return $this->element->getNamespace();
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
		$this->element->setNamespace($namespace);
	}

   /**
	* Initializes the attribute collection for the patForms element that will
	* be used for this subelement by copying over the default attributes for
	* the selected mode, creating the ID and retrieving values depending on the
	* selected mode.
	*
	* @access	private
	*/
	function _initAttributes()
	{
		$this->attributes = $this->defaultAttributes[$this->mode];

		$this->attributes['id'] = $this->parentID.'-'.$this->id;

		if( $this->mode == 'presets' ) {
			$this->attributes['values'] = $this->getValues();
		}

		// give the element the possibility to init its own attributes
		$this->initAttributes();
	}

   /**
	* Initializes the attribute collection. Abstract method that each subelement
	* can use to intialize its own special attributes.
	*
	* @abstract
	* @access	public
	*/
	function initAttributes()
	{
		// code in the subelement class
	}

   /**
	* Checks whether a value exists in the element's value list. Used
	* by the date element to check or validate date strings according
	* to the specified format.
	*
	* @access	public
	* @param	string	$value	The value to check
	* @return	bool	$exists	True if it exists, false otherwise.
	*/
	function valueExists( $value )
	{
		if( $this->mode != 'presets' ) {
			return true;
		}

		foreach( $this->attributes['values'] as $row => $set ) {
			if( $set['value'] == $value ) {
				return true;
			}
		}

		return false;
	}

   /**
	* Retrieves the element's values as a flat string from the element's
	* values collection. Used in error messages.
	*
	* @access	public
	* @return	string	$value	The values list.
	*/
	function getValuesFlat()
	{
		$els = array();
		foreach( $this->attributes['values'] as $row => $set ) {
			array_push( $els, $set['value'] );
		}

		return implode( ', ', $els );
	}

   /**
	* Sets the default timestamp the element will use if no value
	* is set.
	*
	* @access	public
	* @param	string	$date	The timestamp
	*/
	function setDefault( $date )
	{
		$this->defaultDate =& $date;

		$methodName = $this->getterMethod();
		$value = $this->defaultDate->$methodName();

		if( $this->initDone ) {
			$this->element->setAttribute( 'default', $value );
		} else {
			$this->setDefaultAttribute( 'default', $value );
		}

		return true;
	}

   /**
	* Can be used to set/add an attribute from the default attributes
	* collection, for the current mode
	*
	* @access	private
	* @param	string	$attributeName	The attribute to set
	* @param	mixed	$value			The valueto set the attribute to
	* @see		$defaultAttributes
	*/
	function setDefaultAttribute( $attributeName, $value )
	{
		$this->defaultAttributes[$this->mode][$attributeName] = $value;
	}

   /**
	* Sets the maximum timestamp the element will use for validation
	*
	* @access	public
	* @param	string	$date	The timestamp
	*/
	function setMax( $date )
	{
		$this->maxDate =& $date;
		return true;
	}

   /**
	* Sets the maximum timestamp the element will use for validation
	*
	* @access	public
	* @param	string	$date	The timestamp
	*/
	function setMin( $date )
	{
		$this->minDate =& $date;
		return true;
	}

   /**
	* Retrieves the compatible token for the current token if a compatible
	* token is defined.
	*
	* @access	private
	* @param	string	$token	Optional token to check
	* @return	string	$token	Teh compatible token, teh original token otherwise
	* @see		$compatTable
	*/
	function getCompatToken( $token = null )
	{
		if( is_null( $token ) ) {
			$token = $this->token;
		}

		if( isset( $this->compatTable[$token] ) ) {
			return $this->compatTable[$token];
		}

		return $token;
	}

   /**
	* Makes an attribute of the date element be inherited by the patForms subelement.
	*
	* @access	public
	* @param	string	$attribute	The name of the attribute
	* @param	mixed	$value		The value of the attribute
	*/
	function inheritAttribute( $attribute, $value )
	{
		foreach( $this->defaultAttributes as $mode => $attributes ) {
			$this->defaultAttributes[$mode][$attribute] = $value;
		}
	}

   /**
	* Wrapper for the patForms element's setSubmitted method. Stores the
	* state internally when the element has not been created yet, and it
	* will be set on creation.
	*
	* @access	public
	* @param	bool	$state	True = submitted, false = not submitted
	* @see		$submitted
	*/
	function setSubmitted( $state )
	{
		$this->submitted = $state;

		if( !is_null( $this->element ) ) {
			$this->element->setSubmitted( $state );
		}
	}

   /**
	* Wrapper for the patForms element's getValue() method, in which the value
	* is reformatted as the specified format requires to make sure there are no
	* glitches.
	*
	* @access	public
	* @return	string	$value	The value
	*/
	function getValue()
	{
		$value = $this->element->getValue();
		if( strlen( $value ) < 1 ) {
			return null;
		}

		return sprintf( $this->tokens[$this->getCompatToken()]['format'], $value );
	}

   /**
	* Sets the element's value. If the element has not been created yet,
	* the value is stored internally and given the element on creation.
	*
	* @access	public
	* @param	string	$date	The timestamp to create the value from
	*/
	function setValue( &$date )
	{
		$this->date =& $date;

		if( !is_null( $this->element ) ) {
			$methodName = $this->getterMethod();
			$value = sprintf(
				$this->tokens[$this->getCompatToken()]['format'],
				$this->date->$methodName()
			);

			$this->element->setValue( $value );
		}
	}

	function getterMethod()
	{
		return $this->tokens[$this->token]['getter'];
	}

	function setterMethod()
	{
		return $this->tokens[$this->token]['setter'];
	}

   /**
	* Validates the element.
	*
	* @abstract
	* @access	public
	* @return	bool	$valid	True if valid, false otherwise
	*/
	function validate()
	{
		return true;
	}
}

?>