<?php
/**
 * patForms String form renderer that renders a form with a string
 * template by replacing placeholders with the serialized elements.
 *
 * $Id: String.php,v 1.2 2006/05/26 08:09:39 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Renderer
 */

/**
 * Error: the template file could not be found
 */   
define( 'PATFORMS_RENDERER_STRING_ERROR_TEMPLATEFILE_404', 'Renderer:String:001' );

/**
 * flag to define whether errors should be rendered
 */
define('PATFORMS_RENDERER_STRING_RENDER_ERRORS', 'renderErrors');

/**
 * patForms String form renderer that renders a form with a string
 * template by replacing placeholders with the serialized elements.
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Renderer
 * @author		Stephan Schmidt <argh@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Renderer_String extends patForms_Renderer
{
   /**
	* Stores the template string used to render the form
	*
	* @access	private
	* @var		string
	*/
	var	$_template	=	null;

   /**
	* Stores the name of the placeholders to use to insert
	* the elements and element attributes.
	*
	* @access	private
	* @var		string
	*/
	var	$_placeholder = '{PATFORMS_ELEMENT_%s}';
	
   /**
	* Stores the name of the place holder attribute
	*
	* @access	private
	* @var		string
	*/
	var $_placeholderAttribute = 'id';

   /**
	* Stores the name of the placeholder to use for the
	* opening form tag.
	*
	* @access	private
	* @var		string
	*/
	var $_placeholderFormStart = '{PATFORMS_FORM_%s_START}';
	
   /**
	* Stores the name of the placeholder to use for the
	* closing form tag.
	*
	* @access	private
	* @var		string
	*/
	var $_placeholderFormEnd = '{PATFORMS_FORM_%s_END}';

   /**
	* Stores the name of the placeholder to use for the
	* form errros
	*
	* @access	private
	* @var		string
	*/
	var $_placeholderFormErrors = '{PATFORMS_FORM_%s_ERRORS}';
	
   /**
	* Stores the name of the element attributes that will 
	* be replaced in the template.
	*
	* @access	private
	* @var		array
	*/
	var $_attributes = array(
		'label',
		'title',
	);

   /**
    * The format used for displaying error messages for an element
    *
    * @access  private
    * @var     string
    */
    var $_elementErrorLineFormat = '{ELEMENT_LABEL} : {ERROR_MESSAGE}<br />';

   /**
    * The format used for displaying error messages for a form
    *
    * @access  private
    * @var     string
    */
    var $_formErrorLineFormat = '{ERROR_MESSAGE}<br />';
	
   /**
	* Sets the template string to use to render the form
	*
	* The template can be any ASCII data (HTML,	 text, ...)
	* and has to contain placeholders in the format set
	* in the {@link $_placeholder} property, or the custom
	* format set via {@link setPlaceholder}.
	*
	* @access	public
	* @param	string	$template	The template string
	*/
	function setTemplate( $template )
	{
		$this->_template = $template;
	}
	
   /**
	* Sets the template to use from a file.
	* 
	* @access	public
	* @param	string	$file		The file to load the template from
	* @return	mixed	$success	True on success, a patError object otherwise.
	* @see		setTemplate()
	*/
	function setTemplateFile( $file )
	{
		if( !file_exists( $file ) ) {
			return patErrorManager::raiseError(
				PATFORMS_RENDERER_STRING_ERROR_TEMPLATEFILE_404,
				'The specified template file could not be found',
				'Tried to open file "'.$file.'"'
			);
		}
		
		$tmpl = file_get_contents( $file );
		
		$this->setTemplate( $tmpl );
		
		return true;
	}
	
   /**
	* Sets a list of attributes to replace in the template
	* in addition of the default attributes list.
	*
	* @access	public
	* @param	array	$attributes	The list of attributes
	* @see		$_attributes
	*/
	function setAttributes( $attributes )
	{
		$this->_attributes = array_merge( $this->_attributes, $attributes );
	}
	
   /**
	* Sets the placeholder to use for the elements and the 
	* element attributes.
	*
	* Note: make sure this has a %s where you want the element 
	* ID to be inserted so the replacement will work.
	*
	* @access	public
	* @param	string		The placeholder to use
	* @param	string		Name of the attribute that will be inserted in the placeholder
	*/
	function setPlaceholder( $placeholder, $attribute = 'id' )
	{
		$this->_placeholder			 = $placeholder;
		$this->_placeholderAttribute = $attribute;
	}
	
   /**
	* Sets the placeholders to use for the opening and closing
	* form tags.
	*
	* Note: make sure this has a %s where you want the form 
	* name to be inserted so the replacement will work.
	*
	* @access	public
	* @param	string	$placeholderStart	The placeholder for the opening form tag
	* @param	string	$placeholderEnd		The placeholder for the closing form tag
	* @param	string	$placeholderErrors  The placeholder for the error list
	*/
	function setFormPlaceholders($placeholderStart, $placeholderEnd, $placeholderErrors = '{PATFORMS_FORM_%s_ERRORS}')
	{
		$this->_placeholderFormStart  = $placeholderStart;
		$this->_placeholderFormEnd    = $placeholderEnd;
		$this->_placeholderFormErrors = $placeholderErrors;
	}

   /**
	* Set the format of one error message.
	*
	* Supported placeholders in the format strings are:
	* - {ERROR_MESSAGE}
	* - {ELEMENT_LABEL}
	*
	* @access public
	* @param  string       format for an element error
	* @param  string       format for a form error
	*/
	function setErrorLineFormat($elementError, $formError)
	{
	    $this->_elementErrorLineFormat = $elementError;
	    $this->_formErrorLineFormat    = $formError;
	}
	
   /**
	* Gets the template for the renderer
	*
	* @access	public
	* @return	string	$template	The template string
	*/
	function getTemplate()
	{
		return $this->_template;
	}

   /**
	* Gathers serialized data from all elements, and returns it along with all
	* attributes in a handy array that can directly be added to a template to
	* display the form.
	*
	* @access	public
	* @param	object		Reference to the patForms object
	* @param    array       Arguments for the renderer
	* @return	string		The rendered form	
	*/
	function render(&$patForms, $args = array())
	{
		$form		=	$this->getTemplate();
		$elements	=	$patForms->getElements();

		// go through the elements list and replace each element's
		// placeholders and attribute placeholders.
		$cnt = count( $elements );
		
		for ($i = 0; $i < $cnt; $i++) {

			$el		=	$elements[$i]->serialize();	
			$var	=	sprintf( $this->_placeholder, strtoupper( $elements[$i]->getAttribute($this->_placeholderAttribute) ) );	
			$form	=	str_replace( $var, $el, $form );
			$form	=	$this->_replaceAttributes( $form, $elements[$i] );
		}

		$name = $patForms->getName();
	
		// replace the form's opening tag
		$varName = sprintf( $this->_placeholderFormStart, strtoupper( $name ) );
		$form    = str_replace( $varName, $patForms->serializeStart(), $form );
		
		// replace the form's closing tag
		$varName = sprintf( $this->_placeholderFormEnd, strtoupper( $name ) );
		$form    = str_replace( $varName, $patForms->serializeEnd(), $form );

		if (!isset($args[PATFORMS_RENDERER_STRING_RENDER_ERRORS]) || $args[PATFORMS_RENDERER_STRING_RENDER_ERRORS] !== true) {
			return $form;
		}
		
		// not submitted
        if (!$patForms->isSubmitted()) {
    		$varName = sprintf($this->_placeholderFormErrors, strtoupper($name));
    		$form    = str_replace($varName, '', $form);
            return $form;
        }
        
        // no errors
        if ($patForms->validateForm()) {
    		$varName = sprintf($this->_placeholderFormErrors, strtoupper($name));
    		$form    = str_replace($varName, '', $form);
        	return $form;
        }
		
    	$validationErrors = $patForms->getValidationErrors();
		$errorString = '';

		foreach($validationErrors as $fieldName => $errors) {
			if (empty($errors)) {
				continue;
			}
			$field = &$patForms->getElement($fieldName);

			foreach ($errors as $error) {
			
    			if ($fieldName === '__form') {
        			$template = $this->_formErrorLineFormat;
    			} else {
        			$template = $this->_elementErrorLineFormat;
        			$template = str_replace('{ELEMENT_LABEL}', $field->getAttribute('label'), $template);
    			}
       			$template = str_replace('{ERROR_MESSAGE}', $error['message'], $template);
       			$errorString = $errorString . $template;
			}
		}

		// replace the form's error messages
		$varName = sprintf($this->_placeholderFormErrors, strtoupper($name));
		$form    = str_replace($varName, $errorString, $form);

		return $form;
	}
	
   /**
	* Replaces an element's attributes in the form template
	*
	* Note: only a selection of element attributes are replaced
	* per default; if you want more to be replaced, set them
	* with the {@link setAttributes()} method.
	*
	* Have a look at the {@link $_attributes} property to see
	* which attributes are replaced per default.
	*
	* @access	private
	* @param	string	$form		The form template
	* @param	object	&$element	The element
	* @return	string	$form		The form template, with all needed attributes replaced
	* @see		$_attributes
	*/
	function _replaceAttributes( $form, &$element )
	{
		foreach( $this->_attributes as $attribute )
		{
			$varName = sprintf( $this->_placeholder, strtoupper( $element->getId().'_'.$attribute ) );
			$form = str_replace( $varName, $element->getAttribute( $attribute ), $form );
		}
		
		return $form;
	}
}
?>