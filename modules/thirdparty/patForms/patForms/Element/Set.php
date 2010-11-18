<?php
/**
 * simple multiple select dropdown patForms element
 *
 * $Id: Set.php,v 1.2 2006/05/08 23:57:36 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 */

/**
 * no default value has been set
 */
define( 'PATFORMS_ELEMENT_SET_ERROR_NO_DEFAULT_VALUE_AVAILABLE', 'patForms:Element:Set:01' );

/**
 * simple multiple select dropdown patForms element
 *
 * $Id: Set.php,v 1.2 2006/05/08 23:57:36 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian Mordziol <argh@php-tools.net>
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 */
class patForms_Element_Set extends patForms_Element
{
   /**
	* Stores the name of the element - this is used mainly by the patForms
	* error management and should be set in every element class.
	* @access	public
	*/
	var $elementName	=	'Set';

   /**
	* the type of the element - set this to the type of element you are creating
	* if you want to use the {@link patForms_Element::element2html()} method to
	* create the final HTML tag for your element.
	*
	* @access	public
	* @see		patForms_Element::element2html()
	*/
	var $elementType	=	array(
		'html'	=>	'select',
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
										'outputFormats'	=>	array( 'html' ),
									),
			'size'			=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'default'		=>	'3',
										'outputFormats'	=>	array( 'html' ),
									),
			'maxsize'		=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'default'		=>	'5',
										'outputFormats'	=>	array(),
									),
			'datasource'	=>	array(	'required'		=>	false,
										'format'		=>	'datasource',
										'outputFormats'	=>	array(),
									),
			'multiple'		=>	array(	'required'		=>	false,
										'format'		=>	'string',
										'outputFormats'	=>	array( 'html' ),
									),
			'min'			=>	array(	'required'		=>	false,
										'format'		=>	'int',
										'outputFormats'	=>	array(),
									),
			'max'			=>	array(	'required'		=>	false,
										'format'		=>	'int',
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
		'C'	=>	array(
			1	=>	'Please enter the following information',
			2	=>	'The values given for the element does not match any of the possible values.',
			3	=>	'You have to select at least [MIN] entries.',
			4	=>	'You may not select more than [MAX] entries.',
		),
		'de' =>	array(
			1	=>	'Pflichtfeld. Bitte vervollständigen Sie Ihre Angabe.',
			2	=>	'Die angegebenen Werte stimmen mit keinem der möglichen Werte überein.',
			3	=>	'Bitte wählen Sie mindestens [MIN] Einträge aus.',
			4	=>	'Bitte wählen Sie nicht mehr als [MAX] Einträge aus.',
		),
		'fr' =>	array(
			1	=>	'Ce champ est obligatoire.',
			2	=>	'Votre sélection ne correspond à aucune des valeurs admises.',
			3	=>	'Vous devez sélectionner au moins [MIN] éléments dans la liste.',
			4	=>	'Vous ne pouvez sélectionner qu\'un maximum de [MAX] éléments dans la liste.',
		)
	);

	var	$defaultReadonlyValue  =   array(
		'C'	=>	'No selection',
		'de' =>	'Keine Angabe',
		'fr' =>	'Pas de sélection.',
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
		$this->attributes['datasource']	=&	$dataSource;
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
				'No values set to create a Set field',
				'The Set element ['.$this->attributes['name'].'] has no values to create a list from'
			);
		}

		return $values;
	}

   /**
	* Redefinition of the main patForms_Element method that manages
	* converting string values to the internal format.
	* 
	* @access	public
	* @param	string|array	A single value as string, or a selected values list 
	* @see 		patForms_Element::setValue()
	*/
	function setValue( $value )
	{
		if( !is_array( $value ) ) 
		{
			$value = array( $value );
		}
	
		return parent::setValue( $value );
	}
	
	/**
	* Redefinition of the main patForms_Element method that manages
	* converting string values to the internal format.
	* 
	* @access	public
	* @param	string|array	A single value as string, or a selected values list 
	* @see 		patForms_Element::setValue()
	*/
	function setValues( $value )
	{
		if( !is_array( $value ) ) 
		{
			$value = array( $value );
		}
		
		for ($i=0; $i<count($value); $i++) {
			$value[$i] = $this->_applyFilters($value[$i], 'in', PATFORMS_FILTER_TYPE_PHP);
		}
	
		$this->attributes["values"] = $value;
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
		if( !is_array( $value ) )
		{
			$value	=	array();
		}

		if( $this->attributes['display'] == 'no' )
		{
			return $this->createDisplaylessTag( $value );
		}

		$values	= $this->getValues();
		if( patErrorManager::isError( $values ) ) {
			return $values;
		}

		// add multiple flag
		if ($this->attributes['multiple']	== 'yes' || $this->attributes['multiple']	== 'multiple') {
			$this->attributes['multiple']	=	'multiple';
		} else {
			unset($this->attributes['multiple']);
		}

		// add disabled flag if the edit attribute has been set
		if( isset( $this->attributes['edit'] ) && $this->attributes['edit'] == 'no' )
		{
			$this->attributes['disabled'] = 'yes';
		}

		// automatic size adjustment depending on element value list
		if( $this->attributes['size'] == 'auto' )
		{
			$maxsize	=	count( $values );
			if( $this->attributes['maxsize'] != 'none' && $maxsize > $this->attributes['maxsize'] )
			{
				$maxsize	=	$this->attributes['maxsize'];
			}

			$this->attributes['size']	=	$maxsize;
		}

		$attributeCollection = $this->getAttributesFor( $this->getFormat() );

		// make the name an array
		$attributeCollection['name'] .= '[]';

		$element	=	$this->createTag( 'select', 'opening', $attributeCollection );

		foreach( $values as $line => $optionDef )
		{
			$attribs	=	array(	'value'	=>	$optionDef['value'] );

			if ( isset( $optionDef['disabled'] ) && $optionDef['disabled'] == 'yes' )
			{
			    $attribs['disabled'] = 'disabled';
			}

			if( !empty( $optionDef['value'] ) )
			{
				foreach( $value as $subLine => $val )
				{
					if( $optionDef['value'] == $val )
					{
						$attribs['selected']	=	'selected';
					}
				}
			}

			$element	.=	$this->createTag( 'option', 'full', $attribs, $optionDef['label'] );
		}

		$element	.=	$this->createTag( 'select', 'closing' );

		// and return to sender...
		return $element;
	}

	function createDisplaylessTag( $value )
	{
		if( !is_array( $value ) ) {
			$value = array();
		}

		$this->getAttributesFor( $this->getFormat() );

		$tags = null;
		foreach( $value as $line => $val )
		{
			$tags	.=	$this->createHiddenTag( $val );
		}

		return $tags;
	}

   /**
	* element creation method for the 'HTML' format in the 'readonly' form mode.
	*
	* @access	public
	* @return	string	$value	The element's value
	*/
	function serializeHtmlReadonly( $value )
	{
		$tag = $this->createDisplaylessTag( $value );

		if( $this->attributes['display'] == 'no' )
		{
			return $tag;
		}

		if( empty( $value ) )
		{
			return $this->getReadonlyDefaultValue().$tag;
		}

		$element = null;

		$values = $this->getValues();
		if( patErrorManager::isError( $values ) )
		{
			return $values;
		}

		// build the list of labels for the selected entries
		$parts	=	array();
		foreach( $values as $line => $optionDef )
		{
			foreach( $value as $subLine => $val )
			{
				if( $optionDef['value'] == $val )
				{
					array_push( $parts, $optionDef['label'] );
				}
			}
		}

		// no selected entries
		if( empty( $parts ) )
		{
			return $this->getReadonlyDefaultValue().$tag;
		}

		return implode( ', ', $parts ).$tag;
	}

   /**
	* Redefinition of the method from the patForms Element base class, with added functionality
	* needed for the set element (store several values in an array via several hidden fields)
	* @access	public
	*/
	function createHiddenTag( $value )
	{
		$attribs	=	array(	'type'	=>	'hidden',
								'name'	=>	$this->attributes["name"] . '[]',
								'value'	=>	$value,
							);

		return $this->createTag( 'input', 'full', $attribs );
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
				PATFORMS_ELEMENT_SET_ERROR_NO_DEFAULT_VALUE_AVAILABLE,
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
		if( !is_array( $value ) )
		{
			$value	=	array();
		}

		$values	=	$this->getValues();

		if( $values === false )
		{
	 		$this->valid	=	false;
			return false;
		}

		// required & empty
		if( isset( $this->attributes['required'] ) && $this->attributes['required'] == 'yes' && empty( $value ) )
		{
			$this->addValidationError( 1 );
			return false;
		}

		foreach( $value as $line => $val )
		{
			$found = false;

			foreach( $values as $subLine => $subVal )
			{
				if( $val == $subVal['value'] )
				{
					$found = true;
					break;
				}
			}

			if( !$found )
			{
				$this->addValidationError( 2 );
				return false;
			}
		}

		$amountSelected	=	count( $value );

		if( isset( $this->attributes['min'] ) && $amountSelected < $this->attributes['min'] )
		{
			$this->addValidationError( 3, array( 'min' => $this->attributes['min'] ) );
			return false;
		}

		if( isset( $this->attributes['max'] ) && $amountSelected > $this->attributes['max'] )
		{
			$this->addValidationError( 4, array( 'max' => $this->attributes['max'] ) );
			return false;
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
