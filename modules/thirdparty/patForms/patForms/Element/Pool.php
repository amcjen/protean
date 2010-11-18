<?php
/**
 * patForms Pool element
 *
 * Handles value 'pools' with which a user can select among a pool of
 * values by selecting it in a first select box, and moving it into a
 * target box using javascript.
 *
 * $Id: Pool.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 * @author		gERD Schaufelberger <gerd@php-tools.net>
 * @author		Sebastian Mordziol <argh@php-tools.net>
 */

/**
 * Notice: no default value available for selected locale
 */
 define( 'PATFORMS_ELEMENT_POOL_NOTICE_NO_DEFAULT_VALUE_AVAILABLE', 'patForms:Element:Pool:01' );

/**
 * patForms Pool element
 *
 * Handles value 'pools' with which a user can select among a pool of
 * values by selecting it in a first select box, and moving it into a
 * target box using javascript.
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 * @author		gERD Schaufelberger <gerd@php-tools.net>
 * @author		Sebastian Mordziol <argh@php-tools.net>
 * @license		LGPL, see license.txt for details
 *
 * @todo		javascript must be more flexible - maybe use a doubleclick for adding/removing values
 * @todo		implement validation, value handling, ...
 * @todo		use the available set element to generate the select boxes to be able to use the extended functions of these elements, like auto sizing.
 * @todo		implement renderer support for layout control
 * @todo        this should support datasources
 * @todo        move javascript to its own file
 */
class patForms_Element_Pool extends patForms_Element
{
   /**
	* Stores the name of the element - this is used mainly by the patForms
	* error management and should be set in every element class.
	* @access	public
	*/
	var $elementName	=	'Pool';

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
			"disabled"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"no",
										"outputFormats"	=>	array( "html" ),
									),
			"multiple"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"yes",
										"outputFormats"	=>	array( "html" ),
									),
			"size"			=>	array(	"required"			=>	false,
										"format"		=>	"int",
										"default"		=>	20,
										"outputFormats"	=>	array( "html" ),
									),
			"candidates"		=>	array(	"required"	=>	true,
										"outputFormats"	=>	array(),
									),
			"candidatetitle"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	false,
										"outputFormats"	=>	array( ),
									),
			"membertitle"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	false,
										"outputFormats"	=>	array( ),
									),
			"titleclass"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	false,
										"outputFormats"	=>	array( ),
									),
			"toolclass"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	false,
										"outputFormats"	=>	array( ),
									),
			"tooladd"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"add",
										"outputFormats"	=>	array( ),
									),
			"toolremove"	=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"remove",
										"outputFormats"	=>	array( ),
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
		),
		"de" =>	array(
			1	=>	"Pflichtfeld. Bitte vervollständigen Sie Ihre Angabe.",
		),
		"fr" =>	array(
			1	=>	"Ce champ est obligatoire.",
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
		// handle display
		if( $this->attributes['display'] == 'no' )
		{
			return $this->createDisplaylessTag( $value );
		}

		$this->attributes["value"]	=	$value;

		if( $this->attributes["edit"] == "no" )
		{
			$this->attributes['disabled']	=	'yes';
		}

		// create element
		$attCollection	=	$this->getAttributesFor( $this->getFormat() );
		$name			=	$attCollection['name'];
		if ($ns = $this->getNamespace()) {
			$ns .= '_';
		}

		$attribs		=	array(
									'type'	=>	'hidden',
									'name'	=>	$name,
									'id'	=>	$ns . $name,
									'value'	=>	'----',
								);

		$element		=	$this->createTag( 'input', 'full', $attribs ) . "\n";

		// add javascript class
		if( !isset( $GLOBALS['_patForms_Element_Pool' . $name ] ) )
		{
			$GLOBALS['_patForms_Element_Pool' . $name ]	=	true;
			$element	.=	$this->_insertJavascript() . "\n";
		}

		// add layout
		$element 	.=	'<table cellspacing="0" cellpadding="0" boder="0">'
	 				.	"\n<tr>";

		// add titles
		if( $this->attributes['candidatetitle'] || $this->attributes['membertitle'] )
		{
			$class		=	$this->attributes['titleclass'];
			if( $class )
			{
				$class	=	" class=\"$class\" ";
			}

			$title		=	$this->attributes['candidatetitle'];
			if( !$title )
			{
				$title	=	'&nbsp';
			}
			$element	.=	"<td$class>$title</td>";

			$title		=	$this->attributes['membertitle'];
			if( !$title )
			{
				$title	=	'&nbsp';
			}
			$element	.=	"<td$class>$title</td>";

			$element	.=	"</tr>\n<tr>";
		}

		// box of candidates
		$element	.=	'<td>';
		$attCollection['id']	=	'candidates_' . $ns . $name;
		$attCollection['name']	=	'candidates_' . $name;

		$element	.=	$this->createTag( 'select', 'opening', $attCollection );
		$element	.=	$this->createTag( 'select', 'closing' );
		$element	.=	'</td>';

		// box of members
		$element	.=	'<td>';
		$attCollection['id']	=	'members_' . $ns . $name;
		$attCollection['name']	=	'members_' . $name;

		if( isset( $attCollection['accesskey'] ) )
		{
			unset( $attCollection['accesskey'] );
		}

		$element	.=	$this->createTag( 'select', 'opening', $attCollection );
		$element	.=	$this->createTag( 'select', 'closing' );
		$element	.=	'</td>';

		// add tools
		$class		=	$this->attributes['toolclass'];
		if( $class )
		{
			$class	=	"class=\"$class\" ";
		}

		$tooladd	=	$this->attributes['tooladd'];
		$toolremove	=	$this->attributes['toolremove'];

		$element	.=	"</tr>\n<tr>";
		$element	.=	'<td><a '.$class.'href="javascript:pool_'. $ns . $name .'.add();">'. $tooladd.'</a></td>';
		$element	.=	'<td><a '.$class.'href="javascript:pool_'. $ns . $name .'.remove();">'. $toolremove.'</a></td>';
		$element	.=	"</tr>\n";

	 	$element 	.=	"</table>\n";

		// add values to javascript
		$element	.=	'<script language="JavaScript1.2" type="text/javascript">'
					.	"\npool_{$ns}{$name} = new pool('{$ns}{$name}');\n";
		$element	.=	$this->_addMembers() . "\n";
		$element	.=	$this->_addCandidates() . "\n";

		$element	.=	"pool_{$ns}{$name}.init();\n"
					.	"</script>\n";

		// and return to sender...
		return $element;
	}

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

		// handle display
		if( $this->attributes['display'] == 'no' )
		{
			return $tag;
		}

		// no selection
		if( empty( $value ) )
		{
			return $this->getReadonlyValue().$tag;
		}

		// selected entries
		$selected = explode( ',', $value );
		$display = array();

		// we want to display the labels of the values,
		// so we get these.
		foreach( $this->attributes['candidates'] as $row => $candidate )
		{
			if( in_array( $candidate['value'], $selected ) )
			{
				array_push( $display, $candidate['label'] );
			}
		}

		return implode( ', ', $display ).'.'.$tag;
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
		if( $this->attributes["required"] == "yes" )
		{
			$required	=	true;
		}

		if( strlen( $value ) == 0 )
		{
			$empty	=	true;
		}

		if( $empty && $required )
		{
			$this->addValidationError( 1 );
			return false;
		}

		return true;
	}

   /**
	* add preselected members
	*
	* @access private
	* @param integer $id
	* @return boolean $result true on success
	* @see
	*/
    function _addMembers()
    {
		$value	=	$this->getValue();
		if( empty( $value ) )
		{
			return '';
		}

		$name	=	$this->attributes['name'];
		if ($namespace = $this->getNamespace()) {
			$name = $namespace . '_' . $name;
		}
		$mems	=	explode( ',', $value );

		$ele	=	array();
		foreach( $mems as $m )
		{
			array_push( $ele, "pool_$name.addMember( '$m' )" );
		}

		return implode( "\n", $ele );
    }

   /**
	* add candidates to list
	*
	* @access private
	* @param integer $id
	* @return boolean $result true on success
	* @see
	*/
    function _addCandidates()
    {
		$name	=	$this->attributes['name'];
		if ($namespace = $this->getNamespace()) {
			$name = $namespace . '_' . $name;
		}
		$cands	=	$this->attributes['candidates'];

		$ele	=	array();
		foreach( $cands as $cand )
		{
			array_push( $ele, "pool_$name.addCandidate( '".$cand['value']."', '".$cand['label']."' )" );
		}

		return implode( "\n", $ele );
    }

   /**
	* Retrieves the default value to display in the element's readonly mode if the
	* user has not selected any entry, according to the selected locale
	*
	* @access	public
	* @return	string	$defaultValue	The default readonly value in the needed locale
	*/
	function getReadonlyValue()
	{
		$lang	=	$this->locale;

		if( !isset( $this->defaultReadonlyValue[$lang] ) )
		{
			patErrorManager::raiseNotice(
				PATFORMS_ELEMENT_POOL_NOTICE_NO_DEFAULT_VALUE_AVAILABLE,
				'There is no default readonly value available for the locale "'.$lang.'", using default locale "C" instead.'
			);

			return $this->defaultReadonlyValue['C'];
		}

		return $this->defaultReadonlyValue[$lang];
	}

   /**
	* insert javascript code that implements a pool-class
	*
	* @access private
	* @param integer $id
	* @return boolean $result true on success
	* @see
	*/
    function _insertJavascript()
    {
		ob_start();
		echo <<<END
<!--
	PatFormsElement: Pool
	Javascript Class: pool
-->
<script language="JavaScript1.2" type="text/javascript">
function pool( name )
{
	this.name			=	name;
	this.formValue		=	false;
	this.formCandidates	=	false;
	this.formMembers	=	false;
	this.memb			=	new	Array();
	this.cand			=	new Array();

	this.addMember		=	poolAddMember;
	this.addCandidate	=	poolAddCandidate;
	this.add			=	poolAdd;
	this.remove			=	poolRemove;
	this.update			=	poolUpdate;
	this.init			=	poolInit;
}

function candidate( id, text )
{
	this.id			=	id;
	this.text		=	text;
	this.isMember	=	false;
}

function poolAddMember( id )
{
	this.memb.push( id );
}

function poolAddCandidate( id, desc )
{
	this.cand.push( new candidate( id, desc ) );
}

function poolInit()
{
	for( var i = 0; i < this.memb.length; ++i )
		for( var j = 0; j < this.cand.length; ++j )
			if( this.memb[i] == this.cand[j].id )
			{
				this.cand[j].isMember	=	true;
				break;
			}

	this.formValue		=	document.getElementById( this.name );
	this.formCandidates	=	document.getElementById( 'candidates_' + this.name );
	this.formMemebers	=	document.getElementById( 'members_' + this.name );

	this.update();
}

function poolUpdate()
{
	for( var i = this.formCandidates.options.length; i > 0; --i )
		this.formCandidates.options[i - 1]	=	null;

	for( var i = this.formMemebers.options.length; i > 0; --i )
		this.formMemebers.options[i - 1]	=	null;

	this.formValue.value	=	null;
	for( var i = 0; i < this.cand.length; ++i )
	{
		m	=	new Option( this.cand[i].text, this.cand[i].id, false, false );
		if( this.cand[i].isMember )
		{
			this.formMemebers.options[ this.formMemebers.options.length ]	=	m;
			if( this.formValue.value )
				this.formValue.value +=	',' + m.value;
			else
				this.formValue.value =	 m.value;
		}
		else
			this.formCandidates.options[ this.formCandidates.options.length ]	=	m;
	}
}

function	poolAdd( )
{
	for( var i = 0; i < this.formCandidates.options.length; ++i )
		if( this.formCandidates.options[i].selected )
			for( var j = 0; j < this.cand.length; ++j )
				if( this.formCandidates.options[i].value == this.cand[j].id )
					this.cand[j].isMember	=	true;
	this.update();
}

function poolRemove()
{
	for( var i = 0; i < this.formMemebers.options.length; ++i )
		if( this.formMemebers.options[i].selected )
			for( var j = 0; j < this.cand.length; ++j )
				if( this.formMemebers.options[i].value == this.cand[j].id )
					this.cand[j].isMember	=	false;

	this.update();
}
</script>
END;
		$script	=	ob_get_contents();
		ob_end_clean();

		return $script;
	}
}
?>
