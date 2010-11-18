<?php
/**
 * Combobox
 *
 * $Id: Combobox.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 */

/**
 * Error: no default value has been set
 */
define( 'PATFORMS_ELEMENT_COMBO_NOTICE_NO_DEFAULT_VALUE_AVAILABLE', 'patForms:Element:Combo:01');

/**
 * Warning: no values to fill the list with
 */
define( 'PATFORMS_ELEMENT_Combo_WARNING_NO_VALUES', 'patForms:Element:Combo:02' );

/**
 * Combobox
 *
 * $Id: Combobox.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 */
class patForms_Element_Combobox extends patForms_Element
{
   /**
	* Stores the name of the element - this is used mainly by the patForms
	* error management and should be set in every element class.
	* @access	public
	*/
	var $elementName = 'Combobox';

   /**
	* javascript that will be displayed only once
	*
	* @access	private
	* @var		array
	*/
	var $globalJavascript	=	array(
										'html' => null
									);

   /**
	* javascript that will be displayed once per instance
	*
	* @access	private
	* @var		array
	*/
	var $instanceJavascript	=	array(
										'html' => "var pfe_[ELEMENT::NAME] = new pFEC_Combo( '[ELEMENT::ID]' );\n"
									);

   /**
	* the type of the element - set this to the type of element you are creating
	* if you want to use the {@link patForms_Element::element2html()} method to
	* create the final HTML tag for your element.
	*
	* @access	public
	* @see		patForms_Element::element2html()
	*/
	var $elementType	=	array(	"html"	=>	"select",
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

			'id'			=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array( 'html' ),
									),
			'name'			=>	array(	'required'		=>	true,
										'format'		=>	'string',
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
			'required'		=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'default'		=>	'yes',
										'outputFormats'	=>	array(),
									),
			'value'			=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array(),
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
			'values'		=>	array(	'required'		=>	false,
										'format'		=>	'values',
										'outputFormats'	=>	array(),
									),
			'disabled'		=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'default'		=>	'no',
										'outputFormats'	=>	array( 'html' ),
									),
			'datasource'	=>	array(	'required'		=>	false,
										'format'		=>	'datasource',
										'outputFormats'	=>	array(),
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
			1	=>	"Please enter the following information",
			2	=>	"The value given for the element does not match any of the possible values.",
		),
		"de" =>	array(
			1	=>	"Pflichtfeld. Bitte vervollständigen Sie Ihre Angabe.",
			2	=>	"Der angegebene Wert stimmt mit keinem der möglichen Werte überein.",
		),
		"fr" =>	array(
			1	=>	"Ce champ est obligatoire.",
			2	=>	"La valeur de ce champ ne correspond à aucune des valeurs admises.",
		)
	);

   /**
	* Stores the value that will be displayed in readonly mode
	* when no entry has been selected, in the available locales.
	*
	* @access	private
	* @var		array
	*/
	var	$defaultReadonlyValue  =   array(
		"C"	=>	"No selection",
		"de" =>	"Keine Angabe",
		"fr" =>	"Pas de sélection.",
	);

   /**
	* sets the data source for this element. If you set a data source object, the element will
	* ignore the 'values' attribute and request the values from the data source object. The
	* data source object only needs to implement the getValues() method.
	*
	* @access	public
	* @param	object	&$dataSource	The data source to use.
	* @see		dataSource
	*/
	function setDataSource( &$dataSource )
	{
		$this->attributes["datasource"]	=& $dataSource;
	}

   /**
	* retrieves the values to fill the list with. If a data source object has been set,
	* tries to retrieve them from there, otherwise takes them from the 'values' attribute.
	*
	* @access	public
	* @return	mixed	$values	Array with values, or false if failed.
	* @see		setDataSource()
	*/
	function getValues()
	{
		$values = array();

		if( isset( $this->attributes["datasource"] ) ) {
			if (is_object( $this->attributes["datasource"])) {
				$values	=	$this->attributes["datasource"]->getValues($this);
			} else {
				/**
				 * if the datasource is no object, it could
				 * be a callback
				 *
				 * The element will be passed to the callback
				 */
				if (is_callable( $this->attributes["datasource"], false)) {
					$values	= call_user_func( $this->attributes["datasource"], $this);
				}
			}
		}
		if (isset($this->attributes["values"])) {
			$values = array_merge( $this->attributes["values"], $values );
		}
		if (empty($values)) {
			return patErrorManager::raiseWarning(
				PATFORMS_ELEMENT_WARNING_NO_VALUES,
				'No values set to create an Enum field',
				'The Enum element ['.$this->attributes['name'].'] has no values to create a list from'
			);
		}
		return $values;
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
		if ($this->attributes['display'] == 'no') {
			return $this->createDisplaylessTag( $value );
		}

		if ($this->attributes['edit'] == 'no') {
			return $this->serializeHtmlReadonly( $value );
		}

		$values = $this->getValues();
		if (patErrorManager::isError($values)) {
			return $values;
		}

		$attribs = $this->getAttributesFor($this->getFormat());
		$attribs['size'] = 1;
		$element = $this->createTag( 'select', 'opening', $attribs );

		$valueInList = false;
		foreach( $values as $line => $optionDef ) {
			$attribs = array( "value" => $optionDef["value"] );

			if ( isset( $optionDef['disabled'] ) && $optionDef['disabled'] == 'yes' ) {
			    $attribs['disabled'] = 'disabled';
			}

			if( !empty( $optionDef['value'] ) && $optionDef['value'] == $value ) {
				$attribs['selected'] = 'selected';
				$valueInList = true;
			}
			$element .= $this->createTag( 'option', 'full', $attribs, $optionDef['label'] );
		}

		$element .= $this->createTag( 'select', 'closing' );

		$attribs = $this->getAttributesFor($this->getFormat());
		$attribs['name'] .= '__free';
		$attribs['id']   .= '__free';
		$attribs['type'] = 'text';
		if (!$valueInList) {
			$attribs['value'] = $value;
		}

		$element .= $this->createTag( 'input', 'empty', $attribs );

		// and return to sender...
		return $element;
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
		$element	=	null;
		$values		=	$this->getValues();

		if( patErrorManager::isError( $values ) )
		{
			return $values;
		}

		$tag = $this->createDisplaylessTag( $value );

		if( $this->attributes['display'] == 'no' )
		{
			return $tag;
		}

		// empty value -> no entry selected - display the readonly
		// default value instead.
		if( $value === '' )
		{
			return $this->getReadonlyDefaultValue().$tag;
		}

		foreach( $values as $line => $optionDef )
		{
			if( $optionDef["value"] == $value )
			{
				$element	=	$optionDef["label"];
				break;
			}
		}

		if( empty( $element ) )
		{
			$element = $this->getReadonlyDefaultValue();
		}

		return $element.$tag;
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
			patErrorManager::raiseNotice(
				PATFORMS_ELEMENT_ENUM_NOTICE_NO_DEFAULT_VALUE_AVAILABLE,
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
		$values	=	$this->getValues();

		if( $values === false )
		{
	 		$this->valid	=	false;
			return false;
		}

		// required & empty
		if( isset( $this->attributes["required"] ) && $this->attributes["required"] == "yes" && strlen( $value ) == 0 )
		{
			$this->addValidationError( 1 );
			return false;
		}

		return true;
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
		$varName = $this->attributes['name'];

		if($this->submitted) {
			if ($this->value = $this->getRequestVar($_POST, $varName)) {
				return true;
			}
			if ($this->value = $this->getRequestVar($_POST, $varName.'__free')) {
				return true;
			}
			if ($this->value = $this->getRequestVar($_GET, $varName)) {
				return true;
			}
			if ($this->value = $this->getRequestVar($_GET, $varName.'__free')) {
				return true;
			}
		}

		if (isset($this->attributes['default'])) {
			$this->value = $this->attributes['default'];
			$this->value = $this->_applyFilters( $this->value, 'in', PATFORMS_FILTER_TYPE_PHP );

			return true;
		}
		return true;
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
			$tagName	=	"$namespace:$tagName";
			$optName	=	"$namespace:Option";
		}
		else
			$optName	=	"Option";

		// get all attributes
		$attributes	=	$this->getAttributes();
		$options = $attributes['values'];
		unset( $attributes['values'] );

		// create valid XML attributes
		foreach( $attributes as $key => $value )
		{
			$attributes[$key]	=	strtr( $value, $this->xmlEntities );
		}

		$tag = $this->createTag( $tagName, "opening", $attributes );
		foreach( $options as $opt)
		{
			$tag .= $this->createTag( $optName, "empty", $opt );
		}
		$tag .= $this->createTag( $tagName, "closing" );

		return $tag;
	}
}
?>