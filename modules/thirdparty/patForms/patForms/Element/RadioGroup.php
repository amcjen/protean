<?php
/**
 * simple radiobutton patForms element that builds and validates radio buttons, with the
 * particularity that it does not generate a fully serialized element, but an array with
 * serialized subelements.
 *
 * $Id: RadioGroup.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 */

/**
 * Error: no default value available for the element
 */
 define( 'PATFORMS_ERROR_RADIOGROUP_NO_DEFAULT_VALUE_AVAILABLE', 9001 );

/**
 * simple radiobutton patForms element that builds and validates radio buttons, with the
 * particularity that it does not generate a fully serialized element, but an array with
 * serialized subelements.
 *
 * $Id: RadioGroup.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian Mordziol <argh@php-tools.net>
 * @license		LGPL, see license.txt for details
 */
class patForms_Element_RadioGroup extends patForms_Element
{
   /**
	* Stores the name of the element - this is used mainly by the patForms
	* error management and should be set in every element class.
	* @access	public
	*/
	var $elementName	=	'RadioGroup';

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

			"id"			=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
									),
			"name"			=>	array(	"required"		=>	true,
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
			"default"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array(),
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
			"position"		=>	array(	"required"		=>	false,
										"format"		=>	"int",
										"outputFormats"	=>	array(),
									),
			"values"		=>	array(	"required"		=>	false,
										"format"		=>	"values",
										"outputFormats"	=>	array(),
									),
			"disabled"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"no",
										"outputFormats"	=>	array( "html" ),
									),
			"datasource"	=>	array(	"required"		=>	false,
										"format"		=>	"datasource",
										"outputFormats"	=>	array(),
									),
			"clicklabel"	=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array(),
									),
		);

   /**
	* elements of the radio group as patForms elements
	* @var		array
	* @access	private
	*/
	var $elements	=	array();

   /**
	* The amount of elements in the radio group
	* @var		int
	* @access	private
	*/
	var $elementCounter = 0;

    /**
     *	define error codes an messages for each form element
     *
     *  @access private
     *  @var	array	$validatorErrorCodes
     */
	var	$validatorErrorCodes  =   array(
		"C"	=>	array(
			1	=>	"A choice is required, please select an option from the list.",
			2	=>	"The value given for the element does not match any of the possible values.",
		),
		"de" =>	array(
			1	=>	"Pflichtfeld. Bitte wählen Sie einen Punkt aus der Liste.",
			2	=>	"Der angegebene Wert stimmt mit keinem der möglichen Werte überein.",
		),
		"fr" =>	array(
			1	=>	"Ce champ est obligatoire. Veuillez sélectionner un élément dans la liste.",
			2	=>	"La valeur de ce champ ne correspond à aucune des valeurs admises.",
		)
	);

   /**
	* default value in the readonly mode
	* @var	string
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
		$this->attributes["datasource"]	=&	$dataSource;
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
				'No values set to create a RadioGroup field',
				'The RadioGroup element ['.$this->attributes['name'].'] has no values to create a list from'
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
		if( $this->attributes['display'] == 'no' )
		{
			return $this->createDisplaylessTag( $value );
		}

		$success = $this->createElementCollection( $value );
		if( patErrorManager::isError( $success ) )
		{
			return $success;
		}

		// a renderer has been set, use that to generate the content.
		if( is_object( $this->renderer ) )
		{
			return $this->renderer->render( $this );
		}

		// no renderer set, create standard code
		$divAtts = array(
			'id'	=>	$this->getAttribute( 'id' ).'Container',
		);

		$html = $this->createTag( 'div', 'opening', $divAtts );

		$cnt = count( $this->elements );
		for( $i=0; $i < $cnt; $i++ )
		{
			$html .= '<div>'.$this->elements[$i]->serialize().' '.$this->elements[$i]->getAttribute( 'label' ).'</div>';
		}

		$html .= '</div>';

		return $html;
	}

   /**
	* Creates the element collection based on the values list of the
	* radio group element.
	*
	* @access	private
	* @return	mixed	$success	True on success, a patError object otherwise.
	*/
	function createElementCollection( $value )
	{
		// don't redo it if this is called again
		if( !empty( $this->elements ) )
			return true;

		// get the value list from which to generate the list of elements
		$values = $this->getValues();
		if( patErrorManager::isError( $values ) )
		{
			return $values;
		}

		$name = $this->getAttribute( 'name' );

		foreach( $values as $line => $optionDef )
		{
			// compute the element id
			if( !isset( $optionDef['id'] ) )
			{
				$optionDef['id'] = $name.'el'.$this->elementCounter;
			}

			// needed additional attributes for each radio button
			$attribs	=	array(
				'required'		=>	'no',
				'id'			=>	$optionDef['id'],
				'value'			=>	$optionDef['value'],
				'label'			=>	$optionDef['label'],
				'clicklabel'	=>	$this->getAttribute( 'clicklabel' ),
			);

			if ( isset( $optionDef['disabled'] ) && $optionDef['disabled'] == 'yes' )
			{
			    $attribs['disabled'] = 'disabled';
			}

			// add checked attribute to active subelement
			if( !empty( $optionDef['value'] ) && $optionDef['value'] == $value )
			{
				$attribs['checked']	=	'checked';
			}

			// add any additional info as attrributes if they are valid attributes
			foreach( $this->attributeDefinition as $attribute => $def )
			{
				$keys = array_keys( $optionDef );

				if( in_array( $attribute, $keys ) )
					$attribs[$attribute] = $optionDef[$attribute];
			}

			$this->addElement(patForms::createElement( $name, 'Radio', $attribs ));

			$this->elementCounter++;
		}

		return true;
	}

   /**
	* Gets the elements of a radiogroup.
	*
	* A radiogroup consists of several elements and thus can
	* be renderered using any renderer.
	*
	* @access	public
	* @return	array
	*/
	function &getElements()
	{
		return $this->elements;
	}

   /**
	* Adds a patForms element object to the radio group's element collection.
	*
	* @access	public
	* @param	object	&$element	The element to add.
	* @see		$elements
	*/
	function addElement( &$element )
	{
		$element->setNamespace($this->getNamespace());

		$this->elementCounter++;
		$this->elements[] = &$element;
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
		$values		=	$this->getValues();
		$element	=	null;

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
			patErrorManager::raiseWarning(
				PATFORMS_ERROR_RADIOGROUP_NO_DEFAULT_VALUE_AVAILABLE,
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
	function validateElement($value)
	{
		$values = $this->getValues();

		if ($values === false) {
	 		$this->valid = false;
			return false;
		}

		if (strlen($value) == 0) {
		    if (!isset($this->attributes['required']) || $this->attributes['required'] !== 'yes') {
                return true;
		    }
			$this->addValidationError(1);
			return false;
		}

		// is value in values list?
		$found = false;
		foreach ($values as $line => $optionDef) {
			if ($optionDef["value"] == $value) {
				$found = true;
				break;
			}
		}

		if (!$found) {
			$this->addValidationError(2);
			return false;
		}
		return true;
	}

	function serializeStart()
	{
		return '';
	}

	function serializeEnd()
	{
		return '';
	}
}
?>