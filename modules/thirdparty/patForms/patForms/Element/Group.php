<?php
/**
 * Group element that can be used as a container for elements.
 *
 * $Id: Group.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Element
 */

/**
 * Group element that can be used as a container for elements.
 *
 * $Id: Group.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL
 */
class patForms_Element_Group extends patForms_Element
{
   /**
	* Stores the name of the element - this is used mainly by the patForms
	* error management and should be set in every element class.
	* @access	public
	*/
	var $elementName	=	'Group';

   /**
	* The radio element uses a renderer to serialize its content, so we set the flag
	* to true here
	*
	* @access	private
	* @var		array
	*/
	var $usesRenderer	=	true;

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
		'label' => array(
			'required'		=>	false,
			'format'		=>	'string',
			'outputFormats'	=>	array(),
		),
		'display' => array(
			'required'		=>	false,
			'format'		=>	'string',
			'default'		=>	'yes',
			'outputFormats'	=>	array(),
		),
		'edit' => array(
			'required'		=>	false,
			'format'		=>	'string',
			'default'		=>	'yes',
			'outputFormats'	=>	array(),
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
		'position' => array(
			'required'		=>	false,
			'format'		=>	'int',
			'outputFormats'	=>	array(),
		),
		'disabled' => array(
		 	'required'		=>	false,
			'format'		=>	'string',
			'default'		=>	'no',
			'outputFormats'	=>	array( 'html' )
		)
	);

   /**
	* stores the element objects of this form.
	* @access	protected
	* @see		addElement()
	*/
	var $elements	=	array();

   /**
	* stores a renderer
	* @access	protected
	* @see		setRenderer()
	*/
	var $renderer		=	null;

    /**
     *	define error codes an messages for each form element
     *
     *  @access private
     *  @var	array	$validatorErrorCodes
     */
	var	$validatorErrorCodes  =   array(
										"C"	=>	array(
														),
										"de" =>	array(
													)
										);

   /**
	* stores the current element count for this form, used to generate the ids for each element
	* @access	protected
	* @see		getElementId()
	*/
	var $elementCounter	=	0;

   /**
    * flag to indicate, whether the element is a container element
    *
    * @access private
    * @var    boolean
    */
    var $containerElement = false;
	
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
		$cnt = count($this->elements);
    	for ($i = 0; $i < $cnt; $i++) {
    		$this->elements[$i]->setLocale($lang);
    	}
        return  true;
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
	function setFormat( $format )
	{
		$this->format = strtolower($format);
		$cnt = count($this->elements);
    	for ($i = 0; $i < $cnt; $i++) {
    		$this->elements[$i]->setFormat($format);
    	}
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
		$this->mode	= strtolower( $mode );
		$cnt = count($this->elements);
    	for ($i = 0; $i < $cnt; $i++) {
    		$this->elements[$i]->setMode($mode);
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
		$this->namespace = $namespace;
		$cnt = count($this->elements);
    	for ($i = 0; $i < $cnt; $i++) {
    		$this->elements[$i]->setNamespace($namespace);
    	}
	}

   /**
    * sets a renderer object that will be used to render
	* the form.
	*
	* @access	public
	* @param	object	renderer object
	* @see		renderForm()
	*/
	function setRenderer( &$renderer )
	{
		if (!is_object($renderer)) {
			return patErrorManager::raiseError(
				PATFORMS_ERROR_INVALID_RENDERER,
				"No patForms_Renderer object supplied"
			);
		}

		$this->renderer = &$renderer;
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
		$cnt = count($this->elements);
    	for ($i = 0; $i < $cnt; $i++) {
    		$this->elements[$i]->attachObserver($observer);
    	}
		return true;
	}

   /**
	* adds an element to the form - has to be a patForms_Element object. Use the {@link createElement()}
	* method to create a new element object. Also takes care of passing on the form's configuration
	* including the mode, format and submitted flags to the element.
	*
	* @access	public
	* @param	object	&$element	The patForms_Element object to add to this form.
	* @return	bool	$success	True if everythng went well, false otherwise.
	* @see		patForms_Element
	* @see		createElement()
	*/
	function addElement(&$element)
	{
		if (!is_object($element)) {
			return patErrorManager::raiseError(
				PATFORMS_ERROR_ELEMENT_IS_NO_OBJECT,
				"Given element is not an object"
			);
		}

		if (patErrorManager::isError($element)) {
			return patErrorManager::raiseError(
				PATFORMS_ERROR_UNEXPECTED_ERROR,
				"Unexpected Error Object!"
			);
		}

		if (!$element->getId()) {
			$element->setId($this->getElementId());
		}
		$element->setMode($this->getMode());
		$element->setFormat($this->getFormat());
		$element->setSubmitted($this->getSubmitted());
		$element->setNamespace($this->getNamespace());

		$this->elements[] = &$element;
		return true;
	}

   /**
	* retreives a new element id, used to give each added element a unique id for this
	* form (id can be overwritten by setting the id attribute specifically).
	*
	* @access	private
	* @return	int	$elementId	The new element id.
	*/
	function getElementId()
	{
		$this->elementCounter++;
		return "pfo" . $this->getName() . $this->elementCounter;
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
		// manage display attribute. If set, only the needed hidden
		// elements for the subelements will be created.
		if( $this->attributes['display'] == 'no' )
		{
			return $this->createDisplaylessTag( $value );
		}

		if( $this->renderer === null )
		{
			return patErrorManager::raiseError(
				PATFORMS_ERROR_NO_RENDERER_SET,
				"No renderer has been set."
			);
		}

		// edit attribute is inherited by all subelements
		if( $this->attributes['edit'] == 'no' )
		{
			$cnt = count( $this->elements );

			for( $i=0; $i < $cnt; $i++ )
			{
				$this->elements[$i]->setAttribute( 'edit', 'no' );
			}
		}

		return $this->renderer->render( $this );
	}

   /**
	* rewritten for the speciality of the group - creates a collection
	* of hidden elements for all subelements of the group.
	*
	* @access	public
	* @param	mixed	value of the element
	* @return	string	$value	The element's value
	*/
	function createDisplaylessTag( $value )
	{
		$this->getAttributesFor( $this->getFormat() );

		$tag = '';
		$cnt = count( $this->elements );

		for( $i=0; $i < $cnt; $i++ )
		{
			$this->elements[$i]->setAttribute( 'display', 'no' );
			$tag .= $this->elements[$i]->serialize();
		}

		return $tag;
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
		// manage display attribute.
		if( $this->attributes['display'] == 'no' )
		{
			return $this->createDisplaylessTag( $value );
		}

		return $this->renderer->render( $this );
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
		$valid = true;

		$cnt = count($this->elements);
		for ($i = 0; $i < $cnt; ++$i) {
			if (!$this->elements[$i]->validate()) {
				$valid = false;
			}
		}
		return $valid;
	}

   /**
	* Get an element by its name.
	*
	* @access	public
	* @param	string	$name	name of the element
	* @return	mixed			either a patForms element or an array containing patForms elements
	* @see		getElementById()
	*/
	function &getElementByName( $name )
	{
		if( $name == '__form' ) {
			return $this;
		}

		$elements = array();
		$cnt      = count( $this->elements );
		for ($i = 0; $i < $cnt; $i++) {
			if ($this->elements[$i]->getName() == $name) {
				$elements[]	= &$this->elements[$i];
				continue;
			}
			if (method_exists($this->elements[$i], 'getElementById')) {
				patErrorManager::pushExpect(PATFORMS_ERROR_ELEMENT_NOT_FOUND);
				$result = &$this->elements[$i]->getElementByName($name);
				patErrorManager::popExpect();
				if (!patErrorManager::isError($result)) {
					if (is_array($result)) {
						$cnt2 = count( $result );
						for ($j = 0; $j < $cnt2; $j++) {
							$elements[]	= &$result[$j];
						}
					} else {
						$elements[]	= &$result;
					}
				}
			}
		}

		switch( count( $elements ) )
		{
			case	0:
				return patErrorManager::raiseError(
					PATFORMS_ERROR_ELEMENT_NOT_FOUND,
					'Element '.$name.' could not be found.'
				);
				break;
			case	1:
				return	$elements[0];
				break;
			default:
				return	$elements;
				break;
		}
	}

   /**
	* Get an element by its id.
	*
	* @access	public
	* @param	string	$id		id of the element
	* @return	object			patForms element
	*/
	function &getElementById( $id )
	{
		$cnt	=	count( $this->elements );
		for( $i = 0; $i < $cnt; $i++ )
		{
			if( $this->elements[$i]->getId() == $id ) {
				return $this->elements[$i];
			}
			if (method_exists($this->elements[$i], 'getElementById')) {
				patErrorManager::pushExpect(PATFORMS_ERROR_ELEMENT_NOT_FOUND);
				$result = &$this->elements[$i]->getElementById($id);
				patErrorManager::popExpect();
				if (!patErrorManager::isError($result)) {
					return $result;
				}
			}
		}
		return patErrorManager::raiseError(
			PATFORMS_ERROR_ELEMENT_NOT_FOUND,
			'Element '.$name.' could not be found.'
		);
	}

   /**
	* Get all elements of the group
	*
	* @access	public
	* @return	array	all elements of the group
	*/
	function &getElements()
	{
		return	$this->elements;
	}

   /**
	* serialize start of group
	*
	* @return	null
	*/
	function serializeStart()
	{
		return null;
	}

   /**
	* serialize end of group
	*
	* @return	null
	*/
	function serializeEnd()
	{
		return null;
	}

	/**
	* getValidationErrors
	*
	* @access	public
	* @return 	array	errors that occured during the validation
	*/
    function getValidationErrors()
    {
    	$this->validationErrors = array();
    	foreach ($this->elements as $element) {
    		$childErrors = $element->getValidationErrors();
    		$this->validationErrors = array_merge($this->validationErrors, $childErrors);
    	}
    	return parent::getValidationErrors();
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
		$this->submitted = $state;
		$cnt = count($this->elements);
    	for ($i = 0; $i < $cnt; $i++) {
    		$this->elements[$i]->setSubmitted($state);
    	}
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
		$value = array();
		$cnt = count($this->elements);
		for ($i = 0; $i < $cnt; $i++) {
			$elName = $this->elements[$i]->getName();
			$elVal  = $this->elements[$i]->getValue($external);
			$value[$elName] = $elVal;
		}
		return $value;
	}

   /**
	* sets the value of the element, which will be used to fill the element with. If none is
	* set and the element needs a value, it will load it using the {@link resolveValue()} method.
	*
	* @access	public
	* @param	mixed	$value	The value to set
	* @see		$value
	* @see		resolveValue()
	* @see		getValue()
	*/
	function setValue($value)
	{
		patErrorManager::pushExpect(PATFORMS_ERROR_ELEMENT_NOT_FOUND);
		foreach ($values as $elName => $value) {
			$el = &$this->getElementByName($elName);
			if (patErrorManager::isError($el)) {
				continue;
			}
			$el->setValue($value);
		}
		patErrorManager::popExpect();
		return true;
	}

   /**
	* sets the default value of the element, which will be used to fill the element with.
	*
	* @access	public
	* @param	mixed	$value	The value to set
	* @see		$value
	* @see		resolveValue()
	* @see		getValue()
	*/
	function setDefaultValue($value)
	{
		patErrorManager::pushExpect(PATFORMS_ERROR_ELEMENT_NOT_FOUND);
		foreach ($values as $elName => $value) {
			$el = &$this->getElementByName($elName);
			if (patErrorManager::isError($el)) {
				continue;
			}
			$el->setDefaultValue($value);
		}
		patErrorManager::popExpect();
		return true;
	}

   /**
	* replaces an element in the form
	*
	* @access	public
	* @param	object	$element	The patForms_Element object to be replaced
	* @param	object	&$replace	The element that will replace the old element
	* @return	bool	$success	True if everything went well, false otherwise.
	* @see		patForms_Element
	* @see		addElement()
	*/
	function replaceElement( $element, &$replace )
	{
		if (is_object($element)) {
			$element = $element->getId();
		}

		$cnt = count($this->elements);
		for ($i = 0; $i < $cnt; $i++) {
			if ($this->elements[$i]->getId() !== $element) {
				continue;
			}

			if( !$replace->getId() ) {
				$replace->setId( $this->getElementId() );
			}
			$replace->setMode( $this->getMode() );
			$replace->setFormat( $this->getFormat() );
			$replace->setSubmitted( $this->isSubmitted() );
			$replace->setLocale( $this->getLocale() );

			$this->elements[$i] = &$replace;
			return true;
		}
		return false;
	}
}
?>