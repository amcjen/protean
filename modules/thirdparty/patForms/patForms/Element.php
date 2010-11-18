<?php
/**
 * base patForms element class with all needed base functionality that each element
 * should have.
 *
 * $Id: Element.php,v 1.3 2006/06/21 02:56:09 eric Exp $
 *
 * @package		patForms
 * @subpackage	Element
 * @access		protected
 * @author		Sebastian Mordziol <argh@php-tools.net>
 * @author		gERD Schaufelberger <gerd@php-tools.net>
 * @author		Stephan Schmidt <schst@php-tools.net>
 */

/**
 * error definition: the attribute that was set is not supported by this element (it is
 * not listed in the attributeDefinition property set in the element class).
 * @see	patForms_Element::attributeDefinition
 */
define( "PATFORMS_ELEMENT_NOTICE_ATTRIBUTE_NOT_SUPPORTED", 1101 );

/**
 * error definition: the setAttributes() method expects an array,
 * but given value was not.
 * @see	patForms_Element::setAttributes()
 */
define( "PATFORMS_ELEMENT_ERROR_ARRAY_EXPECTED", 1102 );

/**
 * error definition: the given attribute could not be set
 */
define( "PATFORMS_ELEMENT_ERROR_ADDING_ATTRIBUTE_FAILED", 1103 );

/**
 * error definition: the element method to serialize the element in the given mode is
 * not implemented.
 * @see	patForms_Element::serialize()
 */
define( "PATFORMS_ELEMENT_ERROR_METHOD_FOR_MODE_NOT_AVAILABLE", 1104 );

/**
 * error definition: the element returned an error
 */
define( "PATFORMS_ELEMENT_ERROR_ERROR_RETURNED", 1105 );

/**
 * error definition: the modifier that was set for the element is not an array.
 * @see	patForms_Element::_applyModifiers()
 */
define( "PATFORMS_ELEMENT_ERROR_MODIFIER_NOT_AN_ARRAY", 1107 );

/**
 * error definition: the method for the given modifier does not exist
 * @see	patForms_Element::_applyModifiers()
 */
define( "PATFORMS_ELEMENT_ERROR_METHOD_FOR_MODIFIER_NOT_FOUND", 1108 );

/**
 * error definition: the modifier returned an error, modifications could not be made.
 * @see	patForms_Element::_applyModifiers()
 */
define( "PATFORMS_ELEMENT_ERROR_MODIFIER_RETURNED_ERROR", 1109 );

/**
 * error definition: the given attribute is required for the specified output format.
 * @see	patForms_Element::getAttributesFor()
 */
define( "PATFORMS_ELEMENT_ERROR_ATTRIBUTE_REQUIRED", 1110 );

/**
 * error definition: given modifier could not be applied to specified attribute
 * @see	patForms_Element::getAttributesFor()
 */
define( "PATFORMS_ELEMENT_ERROR_UNABLE_TO_APPLY_MODIFIER_TO_ATTRIBUTE", 1111 );

/**
 * error definition: the given attribute is not available for output in the specified
 * output format.
 * @see	patForms_Element::getAttributesFor()
 */
define( "PATFORMS_ELEMENT_ERROR_ATTRIBUTE_NOT_AVAILABLE_FOR_OUTPUT", 1112 );

/**
 * error definition: format of the attribute could not be verified
 * @see	patForms_Element::getAttributesFor()
 */
define( "PATFORMS_ELEMENT_ERROR_CAN_NOT_VERIFY_FORMAT", 1113 );

/**
 * error definition: the attribute collection of the element could not be validated.
 * @see patForms_Element::toHtml()
 */
define( "PATFORMS_ELEMENT_ERROR_CAN_NOT_VALIDATE_ATTRIBUTE_COLLECTION", 1114 );

/**
 * error definition: validator undefined
 */
define( "PATFORMS_ELEMENT_ERROR_VALIDATOR_ERROR_UNDEFINED", 1115 );

/**
 * error definition: undefined locale for errors output
 */
define( "PATFORMS_ELEMENT_ERROR_VALIDATOR_ERROR_LOCALE_UNDEFINED", 1116 );

/**
 * error definition: the html source for the element could not be generated.
 */
define( "PATFORMS_ELEMENT_ERROR_NO_HTML_CONTENT", 1221 );

/**
 * error definition: not a valid renderer
 */
define( 'PATFORMS_ELEMENT_ERROR_INVALID_RENDERER', 1222 );

/**
 * error definition: this element does not support the use of a renderer
 */
define( 'PATFORMS_ELEMENT_RENDERER_NOT_SUPPORTED', 1223 );

/**
 * no values supplied
 */
define('PATFORMS_ELEMENT_WARNING_NO_VALUES',1224);

/**
 * error definition: this element type can't be converted to another type
 */
define( 'PATFORMS_ELEMENT_ERROR_TYPE_NOT_CONVERTABLE', 1224 );

/**
 * error definition: this element value can't be converted to the requested element type
 */
define( 'PATFORMS_ELEMENT_ERROR_VALUE_NOT_CONVERTABLE', 1225 );

/**
 * The filter is invalid
 */
define('PATFORMS_ELEMENT_ERROR_FILTER_INVALID', 1300);

/**
 * filter is located between patForms and browser
 */
define( 'PATFORMS_FILTER_TYPE_HTTP', 1 );

/**
 * filter is located between patForms and the PHP script
 */
define( 'PATFORMS_FILTER_TYPE_PHP', 2 );



/**
 * base patForms element class with all needed base functionality that each element
 * should have. Extend this class to create your own elements.
 *
 * $Id: Element.php,v 1.3 2006/06/21 02:56:09 eric Exp $
 *
 * @abstract
 * @package		patForms
 * @subpackage	Element
 * @access		protected
 * @author		Sebastian Mordziol <argh@php-tools.net>
 * @author		gERD Schaufelberger <gerd@php-tools.net>
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Element
{
   /**
	* the type of the element, set this in your element class!
	* @access	protected
	*/
	var $elementType	=	false;

   /**
	* javascript that will be displayed only once
	*
	* @access	private
	* @var		array
	*/
	var $globalJavascript	=	array();

   /**
	* javascript that will be displayed once per instance
	*
	* @access	private
	* @var		array
	*/
	var $instanceJavascript	=	array();

   /**
	* the value of the element
	* @access	protected
	*/
	var $value		=	false;

   /**
	* filters that have been applied
	* @access	private
	*/
	var $filters	=	array();

   /**
    * attribute filters that have been applied
    * @access   protected
    */
    var $attributeFilters    =   array();

   /**
	* observers that have been attached
	*
	* @access	private
	* @var		array
	*/
	var $observers	=	array();

   /**
	* The elementName for the serialized version of the element
	*
	* This is needed for the toXML() method and also by the patForms
	* error management. If it is not set, the element name will be
	* created by extracting everything after the last underscore in
	* the classname.
	*
	* @access	protected
	* @see		toXML()
	*/
	var $elementName	=	null;

   /**
	* the attribute collection of the element
	* @access	private
	* @see		setAttribute()
	* @see		setAttributes()
	* @see		getAttribute()
	* @see		getAttributes()
	*/
	var $attributes	=	array();

   /**
	* the configuration for the attributes supported by the element. Overwrite this
	* in your element class.
	*
	* @abstract
	*/
	var $attributeDefinition	=	array();

   /**
	* Stores the attribute defaults for the element, that will be used
	* if the given attributes have not been set by the user.
	*
	* @abstract
	* @access	private
	* @see		getAttributeDefaults()
	*/
	var $attributeDefaults	=	array();

   /**
	* stores the mode for the element. It defaults to 'default', and is only overwritten if
	* set specifically.
	*
	* @access	protected
	* @see		setMode()
	*/
	var $mode	=	"default";

   /**
	* stores the format for the element. It defaults to 'html', and is only overwritten if
	* set specifically.
	*
	* @access	protected
	* @see		setFormat()
	*/
	var $format	=	"html";

   /**
	* stores the locale to use when adding validation errors. The specified locale has
	* to be set in the validationErrorCodes element class property, otherwise the default
	* 'C' (as in the programming language C => english) will be used.
	*
	* @access	private
	* @var		string	$locale
	* @see		setLocale()
	*/
	var	$locale   =   "C";

   /**
	* the form's namespace
	*
	* @access	private
	* @var		string
	*/
	var	$namespace = '';

   /**
	* stores the flag telling the element whether it has been submitted - this is used by the
	* getValue() method to determine where to get the element's value from.
	* @access	protected
	* @see		getValue()
	*/
	var $submitted	=	false;

   /**
	* stores the flag whether the element is valid
	* @access	protected
	*/
	var $valid	=	true;

   /**
	* stores any validation errors that can occurr during the element's validation process.
	*
	* @access	private
	* @var		array	$validationErrors
	*/
	var	$validationErrors  =   array();

   /**
	* define error codes an messages for each form element
	*
	* @access	protected
	* @var		array	$validatorErrorCodes
	*/
	var	$validatorErrorCodes  =   array();

   /**
	* defines the starting character for the modifier placeholders that can be inserted
	* in the attributes listed as having modifier support.
	*
	* @access	private
	* @var		string	$modifierStart
	*/
	var $modifierStart	=	"[";

   /**
	* defines the starting character for the modifier placeholders that can be inserted
	* in the attributes listed as having modifier support.
	*
	* @access	private
	* @var		string	$modifierStart
	*/
	var $modifierEnd	=	"]";

   /**
	* XML entities
	*
	* @access	protected
	* @see		toXML()
	*/
	var $xmlEntities	=	array(
									"<"	=>	"&lt;",
									">"	=>	"&gt;",
									"&"	=>	"&amp;",
									"'"	=>	"&apos;",
									'"'	=>	"&quot;"
								);
   /**
	* shortcur to the session variables
	* If "false", no session will be used, otherwise it stores the session variables for this element
	*
	* @access private
	* @var	mixed	$sessionVar
	*/
	var	$sessionVar = false;

   /**
	* custom validation rules
	*
	* @access	private
	* @var		array
	*/
	var $_rules			=	array();

   /**
	* next error offset for rules
	* @access	private
	* @var		integer
	*/
	var $nextErrorOffset	=	1000;

   /**
	* stores whether the element uses a renderer to serialize its content
	* @access	private
	* @var		bool
	*/
	var $usesRenderer		=	false;

   /**
	* Stores the renderer object that can be set via the setRenderer method
	* @access	private
	* @var		object
	*/
	var $renderer			=	false;

   /**
	* Stores all element options
	* @access	private
	*/
	var $options	=	array();

   /**
    * flag to indicate, whether the element is a container element
    *
    * @access protected
    * @var    boolean
    */
    var $containerElement = false;
	
   /**
    * id of the last rule that has been added using the format attribute
    *
    * @access protected
    * @var    string
    */
    var $_formatRuleId = null;
    
   /**
	* constructor - extend this in your class if you need to do specific operations
	* on startup. In that case, however, don't forget to call this constructor anyway
	* so that the thing happening here don't get lost.
	*
	* That's easy to do... just add the following line in your constructor:
	* parent::patForms_Element();
	*
	* @access	public
	* @param	mixed	$mode	Optional: the output format, e.g. 'html'
	*/
	function __construct( $format = false )
	{
		if ($format !== false) {
			$this->format = $format;
		}

		$this->loadAttributeDefaults();
	}

	/**
	 *	patForms_Element	constructor for php4
	 *
	 *	@access	private
	 *	@param	integer	$id
	 *	@return boolean $result	true on success
	 *	@see	__construct
	 */
    function patForms_Element( $format = false )
    {
		$this->__construct( $format );
    }

   /**
	* Add any initialization routines for your element in your element class,
	* for everythig your element needs to do after it has been instantiated and
	* the attribute collection has been created.
	*
	* @abstract
	* @access	private
	* @return	mixed	$success	True on success, a patError object otherwise
	*/
    function _init()
    {
    	// your code here
    	return true;
    }

   /**
	* sets the format of the element - this defines which method will be called in your
	* element class, along with the {@link mode} property.
	*
	* @access	public
	* @param	string	$format	The name of the format you have implemented in your element(s). Default is 'html'
	* @see		setFormat()
	* @see		format
	* @see		serialize()
	*/
	function setFormat($format)
	{
		$this->format = strtolower($format);
	}

   /**
	* sets the mode of the element that defines which methods will be called in your
	* element class, along with the {@link format} property.
	*
	* @access	public
	* @param	string	$mode	The mode to set the element to: default|readonly or any other mode you have implemented in your element class(es). Default is 'default'.
	* @see		setFormat()
	* @see		mode
	* @see		serialize()
	*/
	function setMode( $mode )
	{
		$this->mode	=	strtolower( $mode );
	}

   /**
	* sets the locale (language) to use for the validation error messages of the form.
	*
	* @access	public
	* @param	string	$lang
	* @return	bool	$result	True on success
	* @see		$locale
	*/
    function setLocale( $lang )
    {
		$this->locale = $lang;

		// check, whether this is a custom locale
		if (patForms::isCustomLocale($lang)) {
			$errorMessages = patForms::getCustomLocale($lang, 'Element::' . $this->elementName);
			if (is_array($errorMessages)) {
				$this->validatorErrorCodes[$lang] = $errorMessages;
			}
		}

        return  true;
    }

   /**
	* get the element's namespace
	*
	* @access	public
	* @param	string		namespace
	* @return	string
	*/
	function getNamespace()
	{
		return $this->namespace;
	}

   /**
	* set the element's namespace
	*
	* @access	public
	* @param	string		namespace
	* @return	null
	*/
	function setNamespace($namespace)
	{
		$this->namespace = $namespace;
	}

   /**
	* sets the value of the element, which will be used to fill the element with. If none is
	* set and the element needs a value, it will load it using the {@link resolveValue()} method.
	*
	* This will override user input.
	*
	* @access	public
	* @param	mixed	$value	The value to set
	* @see		$value
	* @see		resolveValue()
	* @see		getValue()
	*/
	function setValue($value)
	{
		$value = $this->_applyFilters($value, 'in', PATFORMS_FILTER_TYPE_PHP);
		$this->value = $value;
	}

   /**
	* sets the default value of the element, which will be used to fill the element with.
	*
	* @access	public
	* @param	mixed	$value	The value to set
	* @see		$value
	* @see		resolveValue()
	* @see		setValue()
	* @see		getValue()
	*/
	function setDefaultValue($value)
	{
		$value = $this->_applyFilters($value, 'in', PATFORMS_FILTER_TYPE_PHP);
		$this->setAttribute('default', $value);
	}

   /**
	* sets the current submitted state of the element. Set this to true if you want the element
	* to pick up its submitted data.
	*
	* @access	public
	* @param	bool	$state	True if it has been submitted, false otherwise (default).
	* @see		getSubmitted()
	* @see		$submitted
	*/
	function setSubmitted( $state )
	{
		$this->submitted	=	$state;
	}

   /**
	* sets the internal ID of the element - this is only used by the {@link patForms} class to
	* give each element a unique ID that will be added as ID attribute to each element if the
	* id attribute has not been defined.
	*
	* @access	public
	* @param	string	$id	The id to set for the element
	* @see		getId()
	*/
	function setId( $id )
	{
		$this->attributes['id']	=	$id;
	}

   /**
	* gets the internal ID of the element
	*
	* @access	public
	* @return	string	$id	The id to set for the element
	* @see		setId()
	*/
	function getId()
	{
		return $this->getAttribute( 'id' );
	}

   /**
	* checks whether a given attribute is supported by this element.
	*
	* @access	public
	* @param	string	$attributeName	The name of the attribute to check
	* @return	bool	$hasAttribute	True if it supports the attribute, false otherwise.
	*/
	function hasAttribute( $attributeName )
	{
		if( isset( $this->attributeDefinition[$attributeName] ) )
		{
			return true;
		}

		return false;
	}

   /**
    * adds an attribute to the element's attribut3 collection. If the attribute
    * already exists, it is overwritten.
    *
    * @access	public
    * @param	string	$attributeName	The name of the attribute to add
    * @param	string	$attributeValue	The value of the attribute
    * @return	mixed	$success		True on success, a patError object otherwise
    */
    function setAttribute( $attributeName, $attributeValue )
    {
        if (!isset($this->attributeDefinition[$attributeName])) {
            return patErrorManager::raiseNotice(
                PATFORMS_ELEMENT_NOTICE_ATTRIBUTE_NOT_SUPPORTED,
                'Unknown attribute ['.$attributeName.']',
                'Ignored the attribute as the ['.$this->elementName.'] element does not support it.'
            );
        }

        if (isset($this->attributeDefinition[$attributeName]['setter'])) {
            $setter = $this->attributeDefinition[$attributeName]['setter'];
            call_user_func_array(array(&$this, $setter), array($attributeValue));
        } else {
            $result =   $this->_applyAttributeFilters( $attributeName, $attributeValue, 'set');
            if (patErrorManager::isError($result)) {
                return $result;
            }
            $this->attributes[$attributeName] = $attributeValue;
        }
        return true;
    }

   /**
	* adds several attribute at once to the element's attributes collection.
	* Any existing attributes will be overwritten.
	*
	* @access	public
	* @param	array	$attributes	The attributes to add
	* @return	mixed	$success	True on success, false otherwise
	*/
	function setAttributes($attributes)
	{
		if (!is_array($attributes)) {
			return patErrorManager::raiseError(
				PATFORMS_ELEMENT_ERROR_ARRAY_EXPECTED,
				"Not an array given (setAttributes)"
			);
		}

		foreach ($attributes as $attributeName => $attributeValue) {
			$this->setAttribute($attributeName, $attributeValue);
		}

		return true;
	}

   /**
	* set the value format of the element
	*
	* @access public
	* @param  string	format (=rule) used for validation
	*/
	function setValueFormat($format)
	{
	    // remove the previously set rule
	    if ($this->_formatRuleId !== null) {
	    	$this->removeRule($this->_formatRuleId);
	    }
	    
		switch (strtolower($format)) {
			case 'email':
				$ruleName = 'Email';
				break;
			case 'url':
				$ruleName = 'URL';
				break;
			default:
				$ruleName = $format;
				break;
		}

		$rule = &patForms::createRule($ruleName);
		$this->addRule($rule, PATFORMS_RULE_AFTER_VALIDATION);
		$this->attributes['format'] = $rule->getRuleName();
		$this->_formatRuleId        = $rule->getId();
	}

   /**
    * sets a renderer object that will be used to render
	* the element. Use the serialize() method to retrieve
	* the rendered content of the element.
	*
	* Only enabled in elements that support renderers, like
	* the radio element.
	*
	* @access	public
	* @param	object	&$renderer	The renderer object
	*/
	function setRenderer( &$renderer )
	{
		if( !$this->usesRenderer )
		{
			return patErrorManager::raiseWarning(
				PATFORMS_ELEMENT_RENDERER_NOT_SUPPORTED,
				'The element \''.$this->elementName.'\' does not support the use of renderers - you do not have to set a renderer for this element.'
			);
		}

		if( !is_object( $renderer ) )
		{
			return patErrorManager::raiseError(
				PATFORMS_ELEMENT_ERROR_INVALID_RENDERER,
				'You can only set a patForms_Renderer object with the setRenderer() method, "'.gettype( $renderer ).'" given.'
			);
		}

		$this->renderer	=	&$renderer;
	}

   /**
	* retrieves the value of an attribute.
	*
	* @access	public
	* @param	string	$attribute	The name of the attribute to retrieve
	* @return	mixed	$attributeValue	The value of the attribute, or false if it does not exist in the attributes collection.
	* @see		setAttribute()
	*/
	function getAttribute( $attribute )
	{
		if( !isset( $this->attributes[$attribute] ) )
		{
			return false;
		}

		return $this->attributes[$attribute];
	}

   /**
	* retrieves all attributes, or only the specified attributes.
	*
	* @access	public
	* @param	array	$attributes	Optional: The names of the attributes to retrieve. Only the attributes that exist will be returned.
	* @return	array	$result		The attributes
	* @see		getAttribute()
	*/
	function getAttributes( $attributes = array() )
	{
		if( empty( $attributes ) )
		{
			return $this->attributes;
		}

		$result	=	array();
		foreach( $attributes as $attribute )
		{
			if( $attributeValue = $this->getAttribute( $attribute ) )
			{
				$result[$attribute]	=	$attributeValue;
			}
		}

		return $result;
	}

   /**
	* Loads the default attribute values into the attributes collection. Done directly
	* on startup (in the consructor), so make sure you call this if your element needs
	* this feature and you have implemented a custom constructor in your element.
	*
	* @access	public
	* @return	bool	$success	Always returns true.
	* @see		$attributeDefaults
	*/
	function loadAttributeDefaults()
	{
		foreach( $this->attributeDefinition as $attributeName => $attributeDef )
		{
			if( isset( $attributeDef['default'] ) )
			{
				$this->attributes[$attributeName]	=	$attributeDef['default'];
			}
		}

		return true;
	}

   /**
	* retrieves the current value of the element. If none is set, will try to retrieve the
	* value from submitted form data.
	*
	* @access	public
	* @param	boolean		Determines whether the method is used from an external script
	* @return	mixed		The value, or an empty string if none found.
	* @see		setValue()
	* @see		value
	* @see		resolveValue()
	*/
	function getValue($external = true)
	{
		if ($this->value === false) {
			$this->resolveValue();

			// could not be resolved
			if ($this->value === false) {
				$value = '';
			} else {
				$value = $this->value;
			}
		} else {
			$value = $this->value;
		}

		if ($external === false) {
			return $value;
		}

		$value = $this->_applyFilters($value, 'out', PATFORMS_FILTER_TYPE_PHP);

		return $value;
	}

	/**
	* resolves the scope the value of the element may be stored in, and returns it.
	*
	* @access	protected
	* @see		getValue()
	* @see		value
	*/
	function resolveValue()
	{
		$varName = str_replace('.', '_', $this->getName());
		if($this->submitted) {
			$value = $this->getRequestVar($_GET, $varName);
			if (!is_null($value)) {
				$this->value = $value;
				return true;
			}
			$value = $this->getRequestVar($_POST, $varName);
			if (!is_null($value)) {
				$this->value = $value;
				return true;
			}
		}
	
		if(isset($this->attributes['default'])) {
			$this->value = $this->attributes['default'];
			$this->value = $this->_applyFilters($this->value, 'in', PATFORMS_FILTER_TYPE_PHP);
			return true;
		}
		return true;
	}

	/**
	* Retrieves a request var value from $_GET or $_POST, corrects
	* magic_quotes_gpc ini settings and applies http input filters.
	*
	* The namespace can have a dot separated syntax, i.e. it can be
	* "foo.bar.baz" which would return the value of
	* $_POST['foo']['bar'][baz] or $_GET['foo']['bar'][baz].
	*
	*
	* @access	protected
	* @see		getValue()
	* @see		value
	* @todo		parse element name, if it uses the array syntax
	*/
	function getRequestVar($arr, $name)
	{
		$value = null;

		$namespace = $this->getNamespace();
		if ($namespace) {
			foreach(explode('.', $namespace) as $key) {
				$arr = &$arr[$key];
			}
			if (!empty($arr) AND isset($arr[$name])) {
				$value = $arr[$name];
			}
		} elseif (isset($arr[$name])) {
			$value = $arr[$name];
		}

		if (!is_null($value) AND ini_get('magic_quotes_gpc')) {
			$value = $this->rStripSlashes($value);
		}
		if (!is_null($value)) {
			$value = $this->_applyFilters($value, 'in', PATFORMS_FILTER_TYPE_HTTP);
		}
		return $value;
	}

   /**
	* recursively strip slashes
	*
	* This method is used to 'fix' magic_quotes_gpc.
	*
	* @access	public
	* @param	mixed		user input (get or post)
	* @return	mixed		data with slashes stripped
	*/
	function rStripSlashes( $value )
	{
		if( is_scalar( $value ) )
			return stripslashes( $value );
		if( is_array( $value ) )
		{
			foreach( $value as $key => $val )
			{
				$value[$key] = $this->rStripSlashes( $val );
			}
		}
		return $value;
	}

   /**
    * apply filters to a value
    *
    * @access	private
    * @param	mixed		value
    * @param	string		direction of the filter ('in' or 'out')
    * @param	integer		type of filters to apply
    * @return	mixed		filtered value
    */
    function _applyFilters($value, $dir = 'in', $type = PATFORMS_FILTER_TYPE_PHP)
    {
        if (empty($this->filters)) {
            return $value;
        }

        $cnt = count( $this->filters );
        for ($i = 0; $i < $cnt; $i++) {
            // check for the desired type
            if ($this->filters[$i]->getType() != $type) {
                continue;
            }

            $value = $this->filters[$i]->$dir($value);
        }
        return $value;
    }

   /**
    * apply filters to an attibutes
    *
    * @access   private
    * @param    string      attribute name
    * @param    mixed       attribute value
    * @param    string      event to be handled by filter ('set')
    * @param    integer     type of filters to apply
    * @return   mixed       true on success, patError object on error
    */
    function _applyAttributeFilters($name, &$value, $event = 'set')
    {
        if (empty($this->attributeFilters)) {
            return true;
        }

        $function   =   'on'. $event;

        $cnt = count( $this->attributeFilters );
        for ($i = 0; $i < $cnt; $i++) {
            $result = $this->attributeFilters[$i]->$function($this->elementType, $name, $value);
            if (patErrorManager::isError($result)) {
                return $result;
            }
        }
        return true;
    }

   /**
	* retrieves the current mode of the element
	*
	* @access	public
	* @return	string	$mode	The current element mode
	* @see		setMode()
	* @see		mode
	*/
	function getMode()
	{
		return $this->mode;
	}

   /**
	* retrieves the current format of the element
	*
	* @access	public
	* @return	string	$format	The current element format
	* @see		setFormat()
	* @see		format
	*/
	function getFormat()
	{
		return $this->format;
	}

   /**
	* retrieves the element's current submitted state.
	*
	* @access	public
	* @return	bool	$state	True if it has been submitted, false otherwise.
	* @see		setSubmitted()
	* @see		submitted
	*/
	function getSubmitted()
	{
		return $this->submitted;
	}

   /**
	* retrieves the name of the element
	*
	* @access	public
	* @return	string	$name	name of the element
	* @uses		getAttribute()
	*/
	function getName()
	{
		return $this->getAttribute('name');
	}

   /**
	* check, whether the element is a container element
	*
	* @access public
	* @return boolean
	*/ 
	function isContainerElement()
	{
	    return $this->containerElement;
	}
	
   /**
	* add a custom validation rule
	*
	* @access	public
	* @param	object patForms_Rule	validation rule
	* @param	integer					time, when rule has to be applied, can be before or after validation.
	*									If set to null, it will use the default value as specified in the rule
	* @return	boolean					currently always true
	*/
	function addRule(&$rule, $time = null)
	{
		if (is_null($time)) {
			$time = $rule->getTime();
		}
		$rule->prepareRule($this);

		$this->_rules[count($this->_rules)]	= array('rule' => &$rule, 'time' => $time);
		return true;
	}

   /**
    * remove a previously added rule
    *
    * @access public
    * @param  patForms_Rule|string  RuleId or rule object
    */
	function removeRule($ruleId)
	{
	    if (is_object($ruleId)) {
	    	$ruleId = $rule->getId();
	    }
	   
	    for ($i = 0; $i < count($this->_rules); $i++) {
	        if ($ruleId != $this->_rules[$i]['rule']->getId()) {
	        	continue;
	        }
	        unset($this->_rules[$i]);
	        while (true) {
	            $next = $i + 1;
	            if (!isset($this->_rules[$next])) {
	                unset($this->_rules[$i]);
	            	break;
	            }
                $this->_rules[$i] = $this->_rules[$next];	            
                $i++;
	        }
	    }
	}
	
   /**
	* adds an observer to the element
	*
	* @access	public
	* @param	object patForms_Observer	observer
	* @return	boolean						currently always true
	*/
	function attachObserver( &$observer )
	{
		$this->observers[] = &$observer;
		return true;
	}

   /**
	* dispatches the serialization of the element in the format that was set to the
	* corresponding method in the element class. These methods must be named in the
	* folowing scheme:
	*
	* serialize[format][mode](), e.g. serializeHtmlDefault()
	*
	* @access	public
	* @return	string	$element		The created element according to the specified mode.
	* @see		setFormat()
	* @see		setMode()
	*/
	function serialize()
	{
		$methodName	=	"serialize".ucfirst( $this->getFormat() ).ucfirst( $this->getMode() );

		if( !method_exists( $this, $methodName ) )
		{
			return patErrorManager::raiseError(
				PATFORMS_ELEMENT_ERROR_METHOD_FOR_MODE_NOT_AVAILABLE,
				"Element method for form mode '".$this->getMode()."' (".$methodName.") is not available."
			);
		}

		$value = $this->getValue(false);

		$element = $this->$methodName($value);
		if (patErrorManager::isError($element)) {
			return $element;
		}

		return $element;
	}

   /**
	* Template method that applies rules and calls the elements
	* validation method
	*
	* @final
	* @access	public
	* @return	bool	$success	True on success, false otherwise
	*/
	function validate()
	{
		// apply locale, if the current locale is a custom locale
		if (patForms::isCustomLocale($this->locale)) {
			$cnt = count( $this->_rules );
			for( $i = 0; $i < $cnt; $i++ ) {
				$this->_rules[$i]['rule']->setLocale($this->locale);
			}
		}

		/**
		 * validate custom rules
		 */
		if( !$this->_applyRules( PATFORMS_RULE_BEFORE_VALIDATION ) )
		{
			$this->_announce( 'status', 'error' );
			return false;
		}

		/**
		 * the the unfiltered value
		 */
		$value	=	$this->getValue( false );

        $valid  =   $this->validateElement( $value );
        if( $valid === false )
        {
						$this->_announce( 'status', 'error' );
            return false;
        }

		/**
		 * validate custom rules
		 */
		if( !$this->_applyRules( PATFORMS_RULE_AFTER_VALIDATION ) )
		{
			$this->_announce( 'status', 'error' );
			return false;
		}

		$this->_announce( 'status', 'validated' );
		return true;
	}

   /**
	* validates the given data with the element's validation routines
	* and returns the data with any needed modifications.
	*
	* @abstract
	* @access	private
	* @return	bool	$success	True on success, false otherwise
	*/
	function validateElement()
	{
        // your code here
        return true;
    }

   /**
	* apply rules
	*
	* @access	private
	* @param	integer		time of validation
	* @return	boolean		rules are valid or not
	* @todo		add documentation
	*/
	function _applyRules( $time )
	{
		$valid = true;

		$cnt = count($this->_rules);
		for ($i = 0; $i < $cnt; $i++) {
			if (($this->_rules[$i]['time'] & $time ) != $time) {
				continue;
			}

			$result = $this->_rules[$i]['rule']->applyRule($this, $time);
			if ($result === false) {
				$valid = false;
			}
		}
		return $valid;
	}

   /**
	* finalize the element.
	*
	* Used as a template method.
	*
	* @final
	* @access	protected
	* @return	bool	$success	True on success, false otherwise
	* @uses		finalizeElement() to call the user code
	*/
	function finalize()
	{
		$value	=	$this->getValue( false );
		return $this->finalizeElement( $value );
	}

   /**
	* finalize the element
	*
	* Offers the possibility to process any needed operations after the element
	* has been validated. Implement any tasks that you need to do then here - a
	* good example is the File element, where this method enables the moving of
	* the uploaded file to the correct location.
	*
	* @abstract
	* @access	private
	* @param	mixed	value of the element
	* @return	bool	$success	True on success, false otherwise
	*/
	function finalizeElement( $value )
	{
		return true;
	}

   /**
	* Enables an element option.
	*
	* See the {@link patForms::$options} property for an exhaustive list of available options.
	*
	* @access	public
	* @param	string	$option		The option to enable
	* @param	array	$params		Optional parameters for the option
	* @see		disableOption()
	* @see		$options
	*/
	function enableOption( $option, $params = array() )
	{
		if( !isset( $this->options[$option] ) )
			$this->options[$option]	=	array();

		$this->options[$option]['enabled']	=	true;
		$this->options[$option]['params']	=	$params;
	}

   /**
	* Disables an element option
	*
	* See the {@link patForms::$options} property for an exhaustive list of available options.
	*
	* @access	public
	* @param	string	$option	The option to disable
	* @see		enableOption()
	* @see		$options
	*/
	function disableOption( $option )
	{
		if( !isset( $this->options[$option] ) )
			$this->options[$option]	=	array();

		$this->options[$option]['enabled']	=	false;
	}

   /**
	* Checks whether the given option is enabled.
	*
	* @access	public
	* @param	string	$option		The option to check
	* @return	bool	$enabled	True if enabled, false otherwise.
	* @see		enableOption()
	* @see		disableOption()
	* @see		getOptionParameters()
	* @see		$options
	*/
	function optionEnabled( $option )
	{
		if( !isset( $this->options[$option] ) )
			return false;

		return $this->options[$option]['enabled'];
	}

   /**
	* returns the parameters that have been supplied for an option
	*
	* @access	public
	* @param	string	$option		The option to check
	* @return	array
	* @see		enableOption()
	* @see		disableOption()
	* @see		optionEnabled()
	* @see		$options
	*/
	function getOptionParameters( $option )
	{
		if( !isset( $this->options[$option] ) )
			return false;

		return $this->options[$option]['params'];
	}

   /**
	* returns single parameter that has been supplied for an option
	*
	* @access	public
	* @param	string	$option		The option to check
	* @return	array
	* @see		enableOption()
	* @see		disableOption()
	* @see		optionEnabled()
	* @see		$options
	*/
	function getOptionParameter($option, $parameter)
	{
		if(!isset($this->options[$option]['params'][$parameter])) {
			return false;
		}

		return $this->options[$option]['params'][$parameter];
	}

   /**
	* Convert an element to another element type
	*
	* @static
	* @access	public
	* @param	object	$element	the element to convert
	* @param	string	$type		desired element type
	* @return	object	the new element
	*/
    function convertElement($element, $type)
    {
		if (in_array(strtolower($type), array('date', 'group', 'radiogroup'))) {
			return patErrorManager::raiseError(
				PATFORMS_ELEMENT_ERROR_TYPE_NOT_CONVERTABLE,
				"Elements of type $type can't be converted."
			);
		}
		// since there's no way to fetch the attributeDefinition in a static way
		// we have to create a dummy element here
		$tmp = patForms::createElement('tmp', $type, null);
		$attributeDef = $tmp->attributeDefinition;

		$name = $element->getName();
		$attributes = array();
		foreach($element->getAttributes() as $key => $value) {
			if (isset($attributeDef[$key])) {
				$attributes[$key] = $value;
			}
		}
		$filters = $element->getFilters();
		$value = $element->getValue();

		$element = patForms::createElement($name, $type, $attributes, $filters);

		if ($value) {
			$element->setValue($value);
		}

		return $element;
	}

   /**
	* [helper method] validates the given value according to the specified method.
	*
	* @access	public
	* @param	mixed	$value		The value to validate the format of
	* @param	string	$format		The format to validate the value with
	* @return	bool	$isValid	True if valid, false if invalid or no method exists to validate the format.
	*/
	function validateFormat($value, $format)
	{
	}

   /**
	* get next error offset
	*
	* @access	public
	* @return	integer
	*/
	function getErrorOffset( $requiredCodes = 100 )
	{
		$offset					= $this->nextErrorOffset;
		$this->nextErrorOffset	= $this->nextErrorOffset + $requiredCodes;
		return $offset;
	}

   /**
	* add error codes and messages for validator method
	*
	* @access	public
	* @param	array	defintions
	* @param	integer	offset for the error codes
	*/
	function addValidatorErrorCodes( $defs, $offset = 1000 )
	{
		foreach( $defs as $lang => $codes )
		{
			if( !isset( $this->validatorErrorCodes[$lang] ) ) {
				$this->validatorErrorCodes[$lang]	=	array();
			}

			foreach( $codes as $code => $message ) {
				$this->validatorErrorCodes[$lang][($offset+$code)]	=	$message;
			}
		}
	}

	/**
	* getValidationErrors
	*
	* @access	public
	* @return 	array	errors that occured during the validation
	*/
    function    getValidationErrors()
    {
        return  $this->validationErrors;
    }

	/**
	* addValidationError
	*
	*
	* @access	public
	* @param	integer	$code
	* @param	array	$vars	fill named placeholder with values
	* @return 	boolean $result	true on success
	*/
    function    addValidationError( $code, $vars = array() )
    {
		$error		=	false;
		$lang		=	$this->locale;
		$element	=	$this->getElementName();

		// find error message for selected language
		while( true )
		{
			// error message matches language code
			if( isset( $this->validatorErrorCodes[$lang][$code] ) )
			{
				$error	=	array( "element" => $element, "code" => $code, "message" => $this->validatorErrorCodes[$lang][$code] );
				break;
			}
			// no message found and no fallback-langauage available
			else if ( $lang == "C" )
			{
				break;
			}

			$lang_old	=	$lang;

			// look for other languages
			if( strlen( $lang ) > 5 )
			{
				list( $lang, $trash	) =	explode( ".", $lang );
			}
			else if( strlen( $lang ) > 2 )
			{
				list( $lang, $trash	) =	explode( "_", $lang );
			}
			else
			{
				$lang	=	"C";
			}

			// inform developer about missing language
			patErrorManager::raiseNotice(
				PATFORMS_ELEMENT_ERROR_VALIDATOR_ERROR_LOCALE_UNDEFINED,
				"Required Validation Error-Code for language: $lang_old not available. Now trying language: $lang",
				"Add language definition in used element or choose other language"
			);

		}

		// get default Error!
		if( !$error )
		{
	 		patErrorManager::raiseWarning(
				PATFORMS_ELEMENT_ERROR_VALIDATOR_ERROR_UNDEFINED,
				"No Error Message for this validation Error was defined",
				"Review the error-definition for validation-errors in your element '$element'."
			);
			$error	=	array( "element" => $element, "code" => 0, "message" => "Unknown validation Error" );
		}

		// insert values to placeholders
		if( !empty( $vars ) )
		{
			foreach( $vars as $key => $value )
			{
				$error["message"]	=	str_replace( "[". strtoupper( $key ) ."]", $value, $error["message"] );
			}
		}

		array_push( $this->validationErrors, $error );
		$this->valid	=	false;
        return  true;
    }

   /**
	* applies the specified modifiers to an attribute value, as set in the attribute definition.
	*
	* @access	private
	* @param	mixed	$attributeValue	The value of the attribute to modify
	* @param	array	$modifiers		Array containing the list of modifiers and their options to apply.
	* @return	mixed	$attributeValue	The modified attribute value.
	* @see		createAttributes()
	*/
	function _applyModifiers( $attributeValue, $modifiers )
	{
		if( !is_array( $modifiers ) )
		{
			return patErrorManager::raiseError(
				PATFORMS_ELEMENT_ERROR_MODIFIER_NOT_AN_ARRAY,
				"Modifiers are not an array"
			);
		}

		foreach( $modifiers as $modifier => $modifierOptions )
		{
			// compute method name for this definition and check if it exists
			$modifierMethod	=	"_modifier".ucfirst( $modifier );

			if( !method_exists( $this, $modifierMethod ) )
			{
				return patErrorManager::raiseError(
					PATFORMS_ELEMENT_ERROR_METHOD_FOR_MODIFIER_NOT_FOUND,
					"Method not found for modifier '" . $modifier . "' (".$modifierMethod.") in class '" . get_class( $this ) . "'"
				);
			}

			$modifiedValue	=	$this->$modifierMethod( $attributeValue );

			if( $modifiedValue === false )
			{
				return patErrorManager::raiseError(
					PATFORMS_ELEMENT_ERROR_MODIFIER_RETURNED_ERROR,
					"Modifier '".$modifier."' returned an error."
				);
			}

			$attributeValue	=	$modifiedValue;
		}

		return $attributeValue;
	}

   /**
    * insertSpecials attribute value modifier
	*
	* you can use special placeholders to insert dynamic values into the attribute values.
	* This method inserts the correct information for each placeholder in the given string.
	*
	* @access	private
	* @param	string	$string	The string to insert the specials in
	* @return	string	$string	The string with all needed replacements
	* @see		_applyModifiers()
	* @todo		Maybe make this configurable
	* @todo		Add any other relevant information
	*/
	function _modifierInsertSpecials( $modifyValue, $options = array() )
	{
		if( is_array( $modifyValue ) || is_object( $modifyValue ) || is_array( $this->value ) )
			return $modifyValue;

		// go through each attribute in the attribute definition and replace the strings
		// with the corresponding attribute values.
		foreach( $this->attributeDefinition as $attributeName => $attributeDef )
		{
			// if attribute was not set, strip the variable by setting it to empty.
			$attributeValue	=	"";

			// retrieve real attribute value if it was set
			if( isset( $this->attributes[$attributeName] ) && is_string( $this->attributes[$attributeName] ) )
			{
				$attributeValue	=	$this->attributes[$attributeName];
			}

			$search	=	$this->modifierStart."ELEMENT_".strtoupper( $attributeName ).$this->modifierEnd;

			// make the replacement
			$modifyValue	=	str_replace( $search, $attributeValue, $modifyValue );
		}

		// the element's value is special...
		$modifyValue	=	str_replace( $this->modifierStart."ELEMENT_VALUE".$this->modifierEnd, $this->value, $modifyValue );

		return $modifyValue;
	}

   /**
	* checks the format of an attribute value according to the given format.
	*
	* @access	private
	* @param	mixed	$attributeValue	The attribute value to check
	* @param	string	$format			The format to check the attribute value against
	* @return	bool	$result			True if format check succeeded, false otherwise.
	* @see		createAttributes()
	*/
	function _checkAttributeFormat( $attributeValue, $format )
	{
		return true;
	}

   /**
	* validates the current attribute collection according to the attributes definition
	* and the given output format, and returns the list of valid attributes.
	*
	* @access	private
	* @param	string	$format		The output format to retrieve the attributes for.
	* @return	mixed	$attributes	The list of attributes, or false if failed.
	*/
	function getAttributesFor( $format )
	{
		$attributes	=	array();

		foreach( $this->attributeDefinition as $attributeName => $attributeDef )
		{
			if( !isset( $this->attributes[$attributeName] ) )
			{
				if( $attributeDef["required"] )
				{
					return patErrorManager::raiseError(
						PATFORMS_ELEMENT_ERROR_ATTRIBUTE_REQUIRED,
						'The element "'.$this->getElementName().'" needs the attribute "'.$attributeName.'" to be set.',
						'See the attribute definition of the element class "'.get_class( $this ).'"'
					);
				}

				continue;
			}

			$attributeValue	=	$this->attributes[$attributeName];

			// special case disabled attribute: skip this if it is not set to yes
			// to avoid generating a disabled field anyway (empty HTML attribute)
			if( $attributeName == 'disabled' && $attributeValue != 'yes' )
			{
				continue;
			}

			if( isset( $attributeDef["modifiers"] ) && !empty( $attributeDef["modifiers"] ) )
			{
				$modifiedValue	=	$this->_applyModifiers( $attributeValue, $attributeDef["modifiers"] );
				if( $modifiedValue === false )
				{
					return patErrorManager::raiseError(
						PATFORMS_ELEMENT_ERROR_UNABLE_TO_APPLY_MODIFIER_TO_ATTRIBUTE,
						"Could not apply modifier to attribute '".$attributeName."' (value:'".$attributeValue."')"
					);
				}

				$attributeValue	=	$modifiedValue;

				// store this for later use too
				$this->attributes[$attributeName]	=	$attributeValue;
			}

			if( !in_array( $format, $attributeDef["outputFormats"] ) )
			{
				continue;
			}

			if( isset( $attributeDef["format"] ) )
			{
				if( !$this->_checkAttributeFormat( $attributeValue, $attributeDef["format"] ) )
				{
					return patErrorManager::raiseError(
						PATFORMS_ELEMENT_ERROR_CAN_NOT_VERIFY_FORMAT,
						"Format '".$attributeDef["format"]."' could not be verified for attribute '".$attributeName."' => '".$attributeValue."'"
					);
				}
			}

			$attributes[$attributeName]	=	$attributeValue;
		}

		return $attributes;
	}

   /**
	* [helper method] wrapper for the {@link createTag()} method which automates the tag
	* creation by creating the tag from the current attribute collection and element type.
	*
	* @access	protected
	* @return	mixed	$result	The created tag, or false if failed.
	* @see		elementType
	* @see		attributes
	* @see		createTag()
	*/
	function toHtml()
	{
		$attributes	= $this->getAttributesFor($this->getFormat());
		if(patErrorManager::isError($attributes)) {
			return $attributes;
		}

		return $this->createTag($this->elementType[$this->getFormat()], "full", $attributes);
	}

   /**
	* [helper method] create a hidden field with the given value. Retrieves all other needed
	* attributes from the attributes collection.
	* @access	public
	*/
	function createHiddenTag( $value )
	{
		$attributes = array(
			'type'  => 'hidden',
			'name'  => $this->attributes['name'],
			'value' => $value,
			'id'    => $this->attributes['id'],
		);

		return $this->createTag("input", "full", $attributes);
	}

   /**
	* [helper method] creates a hidden field with the given value. Used for the
	* display=no attribute, and is the same as the createHiddenTag() method, only
	* that the attributes collection is initialized to ensure that any variables
	* in the element's attributes  get replaced.
	*
	* @access	private
	* @param	mixed	$value		The value of the element
	* @return	string	$element	The serialized hidden tag
	* @see		createHiddenTag()
	*/
	function createDisplaylessTag( $value )
	{
		// call this to initialize all attributes. This is needed
		// here to make sure that if there are
		$this->getAttributesFor( $this->getFormat() );

		return $this->createHiddenTag( $value );
	}

   /**
	* [helper method] create an element HTML source from its attribute collection and
	* returns it.
	*
	* @access	protected
	* @param	string	$tagname		The name of the element / tag
	* @param	string	$type			Optional: the type of element to generate. Valid parameters are full|opening|closing|empty. Defaults to "full".
	* @param	mixed	$value			The value of the element
	* @return	string	$element		The HTML source of the element
	*/
	function createTag( $tagname, $type = "full", $attributes = array(), $value = false )
	{
	    patErrorManager::pushExpect(PATFORMS_ERROR_NO_STATIC_PROPERTY);
	    $encoding = patForms::getStaticProperty('defaultEncoding');
	    if (patErrorManager::isError($encoding)) {
	       $encoding = 'ISO-8859-1';
	    }
	    
		switch( $type )
		{
			case "closing":
				return	"</$tagname>";
				break;

			case "empty":
			case "opening":
				$tag	=	"<".$tagname;

				// create attribute collection
				foreach( $attributes as $attributeName => $attributeValue )
				{
					if ($attributeName == 'name') {
						$attributeValue = patForms_Element::adjustNamespace($attributeValue);
					}
					$tag	=	$tag . " ".$attributeName."=\""
					       .htmlentities( (string)$attributeValue, ENT_COMPAT, $encoding )."\"";
				}

				// empty tag?
				if( $type == "empty" )
				{
					$tag	=	$tag . " />";
					return	$tag;
				}

				$tag	=	$tag . ">";
				return	$tag;

				break;

			case "full":
				if( $value === false )
				{
					return patForms_Element::createTag( $tagname, "empty", $attributes );
				}

				return patForms_Element::createTag( $tagname, "opening", $attributes )
				        .htmlentities( $value, ENT_COMPAT, $encoding )
				        .patForms_Element::createTag( $tagname, "closing" );
				break;
		}
	}

   /**
	* Adjusts a given name for element serialization
	*
	* prepends the elements namespace property if not empty
	*
	* @access	protected
	* @param	string	$name		a name
	* @return	string	$name		the adjusted name
	*/
	function adjustNamespace($name) {

		if (!$namespace = $this->getNamespace()) {
			return $name;
		}
		if (strpos($name, '[]') == strlen($name) - 2) {
			// will re-append an empty array operator
			$name = str_replace('[]', '.', $name);
		}
		$tokens = explode('.', $namespace . '.' . $name);
		$name = array_shift($tokens);
		foreach($tokens as $token) {
			$name .= '[' . $token . ']';
		}
		return $name;
	}

   /**
	* create XML representation of the element
	*
	* This can be used when you need to store the structure
	* of your form in flat files or create form templates that can
	* be read by patForms_Parser at a later point.
	*
	* @access	public
	* @param	string		namespace
	* @uses		getElementName()
	* @see		patForms_Parser
	*/
	function toXML( $namespace = null )
	{
		$tagName	=	$this->getElementName();

		// prepend Namespace
		if( $namespace != null )
		{
			$tagName	=	$namespace.':'.$tagName;
		}

		// get all attributes
		$attributes	=	$this->getAttributes();

		// create valid XML attributes
		foreach( $attributes as $key => $value )
		{
			$attributes[$key]	=	strtr( $value, $this->xmlEntities );
		}

		$value	=	strtr( $this->getValue(), $this->xmlEntities );

		if( $value != false )
		{
			return	$this->createTag( $tagName, "full", $attributes, $value );
		}
		return	$this->createTag( $tagName, "empty", $attributes );
	}

   /**
	* apply a filter
	*
	* @access	public
	* @param	object patForms_Filter
	* @return   boolean   true, if the filter could be added
	*/
	function applyFilter(&$filter)
	{
		$this->filters[] = &$filter;
		return true;
	}

   /**
    * apply an attribute filter
    *
    * @access   public
    * @param    object patForms_AttributeFilter
    * @return   mixed true, if the filter could be added, patError object on error
    */
    function applyAttributeFilter(&$filter)
    {
        // remember filter for furhter event handling
        $this->attributeFilters[] = &$filter;

        // run filter now
        foreach ( array_keys( $this->attributes ) as $name) {
            $result =   $filter->onApply($this->elementType, $name, $this->attributes[$name]);
            if (patErrorManager::isError($result)) {
                return $result;
            }
        }
        return true;
    }

   /**
	* Apply a simple filter
	* 
	* To create a simple filter you can supply to
	* functions, one to filter the data you receive
	* from the user and one to filter tha data that
	* is sent to the user.
	*
	* @access	public
	* @param	callback  callback for data entered by the user
	* @param    callback  callback for data sent to the user
	* @return   boolean   true, if the filter could be added
	*/
	function applySimpleFilter($inFunc, $outFunc = null)
	{
		if (!is_null($inFunc) && !is_callable($inFunc)) {
			patErrorManager::raiseError(PATFORMS_ELEMENT_ERROR_FILTER_INVALID, 'The incoming filter is no valid callback.');
		}
		if (!is_null($outFunc) && !is_callable($outFunc)) {
			patErrorManager::raiseError(PATFORMS_ELEMENT_ERROR_FILTER_INVALID, 'The outgoing filter is no valid callback.');
		}
		$filter = &patForms::createFilter('Function');
		if (patErrorManager::isError($filter)) {
			return $filter;
		}
		$filter->setInFunction($inFunc);
		$filter->setOutFunction($outFunc);
		return $this->applyFilter($filter);
	}

   /**
	* return the elements filters
	*
	* @access	public
	* @return   array      list of all registered filter objects
	*/
	function getFilters()
	{
		return $this->filters;
	}

   /**
    * Get the name of the element, as stored in the elementName property.
	*
	* This is used when serializing an element to XML to
	* create a now form template.
	*
	* This method checks for the $elementName property and if it
	* is set to null, it will extract the element name from the class name
	*
	* @access	public
	* @return	string		tag name
	*/
	function getElementName()
	{
		if ($this->elementName != null) {
			return	$this->elementName;
		}

		$name = substr(strrchr(get_class($this), "_"), 1);
		return	ucfirst($name);
	}

   /**
	* checks wheter sessions are used or switch session usage on or of
	*
	* If switch argument is missing, this function just reports if sessions
	* will be used or not
	*
	* @access protected
	* @param string $switch switch sessions on ("yes") or off ("yes")
	* @return boolean $result true if sessions will be used, false otherwise
	* @see setSessionValue()
	* @see getSessionValue()
	* @see unsetSessionValue()
	* @todo destroy session variables if sessions won't be usead any further
	*/
    function useSession( $switch = null )
    {
		// switch sessions on or off
		if( $switch == "yes" )
		{
			$this->attributes["usesession"]	=	"yes";
		}
		else if( $switch == "no" )
		{
			$this->attributes["usesession"]	=	"no";
			return false;
		}

		if( isset( $this->attributes["usesession"] ) && $this->attributes["usesession"] == "yes" )
		{
			if( !$this->sessionVar )
			{
				if( !defined( "SID" ) )
				{
					session_start();
				}

				$name	=	$this->attributes["name"];
				if( !isset( $_SESSION["_patforms_element"][$name] ) )
				{
					$_SESSION["_patforms_element"][$name]	=	array();
				}

				$this->sessionVar	=&	$_SESSION["_patforms_element"][$name];
			}

			return true;
		}
		return false;
    }

   /**
	* save a variable to the session
	*
	* @access protected
	* @param string $name name to identify the variable
	* @param mixed $value
	* @return boolean $result true on success
	* @see getSessionValue()
	* @see unsetSessionValue()
	*/
    function setSessionValue( $name, $value )
    {
		if( !$this->useSession() )
		{
			return false;
		}

		$this->sessionVar[$name]	=	$value;
        return true;
    }

   /**
	* get a variable from session
	*
	* @access protected
	* @param string $name name to identify the variable
	* @return mixed $result false if no sessions are used, null if variable is not set or the value of the variable
	* @see getSessionValue()
	* @see unsetSessionValue()
	*/
    function getSessionValue( $name )
    {
		if( !$this->useSession() )
		{
			return false;
		}

		if( isset( $this->sessionVar[$name] ) )
		{
			return $this->sessionVar[$name];
		}
        return null;
    }

   /**
	* remove a variable from session
	*
	* @access protected
	* @param string $name name to identify the variable
	* @return mixed $result false if no sessions are used, null if variable is not set or the value of the variable
	* @see getSessionValue()
	* @see setSessionValue)
	*/
    function unsetSessionValue( $name )
    {
		if( !$this->useSession() )
		{
			return false;
		}

		$value	=	null;
		if( isset( $this->sessionVar[$name] ) )
		{
			$value	=	$this->sessionVar[$name];
			unset( $this->sessionVar[$name] );
		}
        return $value;
    }

   /**
	* Registers the scripts form this element to the form
	*
	* @access	public
	* @param	string $type   type of the element or rule
	* @param	string $script path to a scriptfile
	* @see		patForms::getScripts()
	* @see		patForms_Element::registerJavascripts()
	* @see		patForms_Rule::registerJavascripts()
	*/
	function registerJavascripts(&$form)
	{
		if($script = $this->getGlobalJavascript()) {
			$form->registerGlobalJavascript($this->elementName, $script);
		}
		if($script = $this->getInstanceJavascript()) {
			$form->registerInstanceJavascript($script);
		}
		foreach($this->_rules as $rule) {
			$rule['rule']->registerJavascripts($form);
		}
	}

   /**
	* get the global javascript of the element
	*
	* @access	public
	* @return	string
	*/
	function getGlobalJavascript()
	{
		if(isset($this->globalJavascript[$this->format])) {
			return $this->globalJavascript[$this->format];
		}
	}

   /**
	* get the instance javascript of the element
	*
	* @access	public
	* @return	string	javascript for this instance
	*/
	function getInstanceJavascript()
	{
		if(isset($this->instanceJavascript[$this->format])) {
			$name = $this->getName();
			if ($namespace = $this->getNamespace()) {
				$name = str_replace('.', '_', $namespace . '_' . $name);
			}

			$script	= $this->instanceJavascript[$this->format];
			$script	= str_replace('[ELEMENT::NAME]', $name, $script);
			$script	= str_replace('[ELEMENT::ID]', $this->getId(), $script);
			return $script;
		}
	}

   /**
	* retrieves the element's current submitted state.
	*
	* @access	public
	* @return	bool	$state	True if it has been submitted, false otherwise.
	* @see		submitted
	*/
	function isSubmitted()
	{
		if ($this->submitted === true) {
			return true;
		}
		return false;
	}

   /**
	* returns the locale that is currently set for the form.
	*
	* @access	public
	* @return	string	$locale	The locale.
	* @see		setLocale()
	* @see		$locale
	*/
	function getLocale()
	{
		return $this->locale;
	}

   /**
	* anounce a change in the element to all observers
	*
	* @access	private
	* @param	string		property that changed
	* @param	mixed		new value of the property
	*/
	function _announce($property, $value)
	{
		$cnt = count($this->observers);
		for ($i = 0; $i < $cnt; $i++) {
			$this->observers[$i]->notify($this, $property, $value);
		}
		return true;
	}

   /**
	* filter a value that has been passed via GET or POST
	*
	* This method checks the magic_quotes_gpc setting and
	* applies all filters that had been set.
	*
	* @access	protected
	* @return	boolean
	*/
	function _filterHttpValue()
	{
		if (ini_get('magic_quotes_gpc')) {
			$this->value = $this->rStripSlashes($this->value);
		}
		$this->value = $this->_applyFilters($this->value, 'in', PATFORMS_FILTER_TYPE_HTTP);
		return true;
	}

   /**
    * convert a value to a human readable version
    *
    * This method should be redefined in the actual element classes.
    *
    * @access   public
    * @param    mixed       value to convert
    * @return   string      converted value
    */
    function convertValueToHumanReadable($value)
    {
        return $value;
    }
}
?>