<?php
/**
 * patForms Renderer for patTemplate
 *
 * $Id: patTemplate.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package       patForms
 * @subpackage    Renderer
 */
 
/**
 * Error: patTemplate is not available
 */   
define('PATFORMS_RENDERER_PATTEMPLATE_ERROR_NO_CLASS', 'Renderer:patTemplate:001');

/**
 * Error: 
 */   
define('PATFORMS_RENDERER_PATTEMPLATE_ERROR_NO_TEMPLATE', 'Renderer:patTemplate:002');

/**
 * patForms Renderer for patTemplate
 *
 * This needs patTemplate 3.0.0 or higher to work.
 *
 * @package       patForms
 * @subpackage    Renderer
 * @author        Stephan Schmidt <schst@php-tools.net>
 * @license       LGPL, see license.txt for details
 * @link          http://www.php-tools.net
 */
class patForms_Renderer_patTemplate extends patForms_Renderer
{
   /**
    * Stores the template object
    *
    * @access    private
    * @var       object patTemplate
    */
    var $_tmpl = null;

   /**
    * Stores the name of the placeholders to use to insert
    * the elements and element attributes.
    *
    * @access    private
    * @var       string
    */
    var $_placeholder = 'PATFORMS_ELEMENT_%s';
    
   /**
    * Stores the name of the place holder attribute
    *
    * @access    private
    * @var       string
    */
    var $_placeholderAttribute = 'id';

   /**
    * Stores the name of the placeholder to use for the
    * opening form tag.
    *
    * @access    private
    * @var       string
    */
    var $_placeholderFormStart = 'PATFORMS_FORM_%s_START';
    
   /**
    * Stores the name of the placeholder to use for the
    * closing form tag.
    *
    * @access    private
    * @var       string
    */
    var $_placeholderFormEnd = 'PATFORMS_FORM_%s_END';

   /**
    * Stores the name of the element attributes that will 
    * be replaced in the template.
    *
    * @access    private
    * @var       array
    */
    var $_attributes = array(
        'label',
        'title',
    );

   /**
    * checks whether errors have been rendered or not
	* @access	private
	*/
	var $errorsRendered = array();
    
   /**
    * Sets the patTemplate object, that will be used to render
    * the page.
    *
    * @access    public
    * @param    object patTemplate
    */
    function setTemplate(&$tmpl)
    {
        $this->_tmpl = &$tmpl;
    }
    
   /**
    * Sets a list of attributes to replace in the template
    * in addition of the default attributes list.
    *
    * @access   public
    * @param    array   The list of attributes
    * @see      $_attributes
    */
    function setAttributes($attributes)
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
    * @access   public
    * @param    string        The placeholder to use
    * @param    string        Name of the attribute that will be inserted in the placeholder
    */
    function setPlaceholder($placeholder, $attribute = 'id')
    {
        $this->_placeholder          = $placeholder;
        $this->_placeholderAttribute = $attribute;
    }
    
   /**
    * Sets the placeholders to use for the opening and closing
    * form tags.
    *
    * Note: make sure this has a %s where you want the form 
    * name to be inserted so the replacement will work.
    *
    * @access   public
    * @param    string    The placeholder for the opening form tag
    * @param    string    The placeholder for the closing form tag
    */
    function setFormPlaceholders($placeholderStart, $placeholderEnd)
    {
        $this->_placeholderFormStart = $placeholderStart;
        $this->_placeholderFormEnd   = $placeholderEnd;
    }
    
   /**
    * Serializes the form elements and renders them using a patTemplate
    * object.
    *
    * The renderer will create a new instance of patTemplate
    *
    * Arguments that can be passed:
    * - tmplName = name of the template to use
    * - basedir  = directory in which templates are stored
    * - tmplFile = load a template file before rendering the form
    *
    * @access   public
    * @param    object        Reference to the patForms object
    * @param    array         optional arguments for the renderer
    * @return   object patTemplate
    */
    function &render(&$patForms, $args = array())
    {
        // create a new template instance
        if ($this->_tmpl === null) {
            if (!class_exists('patTemplate')) {
                return patErrorManager::raiseError(PATFORMS_RENDERER_PATTEMPLATE_ERROR_NO_CLASS, 'No instance of patTemplate has been passed, nor has the class been loaded.');
            }
            $this->_tmpl = &new patTemplate();
        }

        // set the base directory
        if (isset($args['tmplDir'])) {
            $this->_tmpl->setRoot($args['tmplDir']);
        }
        
        // load the template
        if (isset($args['tmplFile'])) {
            $result = $this->_tmpl->readTemplatesFromInput($args['tmplFile']);
            if (patErrorManager::isError($result)) {
            	return $result;
            }
        }
        
        if (isset($args['tmplName']) && !$this->_tmpl->exists($args['tmplName'])) {
        	return patErrorManager::raiseError(PATFORMS_RENDERER_PATTEMPLATE_ERROR_NO_TEMPLATE, 'The template has not been defined.');
        }

        $this->_renderFormTags($patForms, $args);
        
        if (isset($args['elementTemplate'])) {
        	$result = $this->_renderElementsRepeating($patForms, $args);
        } else {
        	$result = $this->_renderElementsStatic($patForms, $args);
        }
        
        if (patErrorManager::isError($result)) {
        	return $result;
        }

		if (!isset($args['errorTemplate'])) {
            return $this->_tmpl;
		}
		
		$this->_renderErrors($patForms, $args);
        return $this->_tmpl;
    }

   /**
    * render the elements by repeating an element
    *
    * @access   private
    * @param    object patForms   Reference to the patForms object
    * @param    array             optional arguments for the renderer
    * @return   boolean
    */
    function _renderElementsRepeating(&$patForms, $args = array())
    {
        $elements = $patForms->getElements();

        // go through the elements list and replace each element's
        // placeholders and attribute placeholders.
        $cnt = count( $elements );
        for ($i = 0; $i < $cnt; $i++) {
            $el  = $elements[$i]->serialize();
            $this->_tmpl->addVar($args['elementTemplate'], $this->_placeholder, $el);
            $varName = $this->_placeholder.'_ELEMENTTYPE';
            $this->_tmpl->addVar($args['elementTemplate'], $varName, $elements[$i]->getElementName());
            foreach ($this->_attributes as $attribute) {
               $varName = $this->_placeholder.'_'.$attribute;
               $this->_tmpl->addVar($args['elementTemplate'], $varName, $elements[$i]->getAttribute($attribute));
            }
            $this->_tmpl->parseTemplate($args['elementTemplate'], 'a');
        }
        return true;
    }

   /**
    * render the elements by adding them to different variables in
    * the same template.
    *
    * This gives you more power over the layout.
    *
    * @access   private
    * @param    object patForms   Reference to the patForms object
    * @param    array             optional arguments for the renderer
    * @return   boolean
    */
    function _renderElementsStatic(&$patForms, $args = array())
    {
        $elements = $patForms->getElements();

        // go through the elements list and replace each element's
        // placeholders and attribute placeholders.
        $cnt = count( $elements );
        for ($i = 0; $i < $cnt; $i++) {
            $el  = $elements[$i]->serialize();
            $var = sprintf($this->_placeholder, $elements[$i]->getAttribute($this->_placeholderAttribute));
            if (isset($args['tmplName'])) {
                $this->_tmpl->addVar($args['tmplName'], $var, $el);
                $varName = $this->_placeholder.'_ELEMENTTYPE';
                $this->_tmpl->addVar($args['tmplName'], $varName, $elements[$i]->getElementName());
                foreach ($this->_attributes as $attribute) {
                    $varName = $var.'_'.$attribute;
                    $this->_tmpl->addVar($args['tmplName'], $varName, $elements[$i]->getAttribute($attribute));
                }
            } else {
                $this->_tmpl->addGlobalVar($var, $el);
                $varName = $this->_placeholder.'_ELEMENTTYPE';
                $this->_tmpl->addGlobalVar($varName, $elements[$i]->getElementName());
                foreach ($this->_attributes as $attribute) {
                    $varName = $var.'_'.$attribute;
                    $this->_tmpl->addGlobalVar($varName, $elements[$i]->getAttribute($attribute));
                }
            }
        }
        
        return true;
    } 
    
   /**
    * render the opening and closing form tag
    *
    * @access   private
    * @param    object patForms   Reference to the patForms object
    * @param    array             optional arguments for the renderer
    * @return   boolean
    */
    function _renderFormTags(&$patForms, $args = array())
    {
        $name = $patForms->getName();

        // replace the form's opening tag
        $varName = sprintf($this->_placeholderFormStart, $name);
        if (isset($args['tmplName'])) {
            $this->_tmpl->addVar($args['tmplName'], $varName, $patForms->serializeStart());
        } else {
            $this->_tmpl->addGlobalVar($varName, $patForms->serializeStart());
        }

        // replace the form's closing tag
        $varName = sprintf($this->_placeholderFormEnd, $name);
        if (isset($args['tmplName'])) {
            $this->_tmpl->addVar($args['tmplName'], $varName, $patForms->serializeEnd());
        } else {
            $this->_tmpl->addGlobalVar($varName, $patForms->serializeEnd());
        }
        return true;
    }

   /**
    * render the errors
	*
	* @access	private
    * @param    object patForms   Reference to the patForms object
    * @param    array             optional arguments for the renderer
    * @return   boolean
	*/
	function _renderErrors(&$patForms, $args = null)
	{
	    // already rendered
		if (isset($this->errorsRendered[$patForms->getName()])) {
			return true;
		}
	    
		// not submitted
        if (!$patForms->isSubmitted()) {
            return true;
        }
        
        // no errors
        if ($patForms->validateForm()) {
        	return true;
        }
    	    
        if (isset($args['errorTemplateContainer'])) {
            $this->_tmpl->setAttribute( $args['errorTemplateContainer'], 'visibility', 'visible' );
        }
    	$validationErrors = $patForms->getValidationErrors();

		foreach($validationErrors as $fieldName => $errors) {
			if (empty($errors)) {
				continue;
			}
			$field =& $patForms->getElement($fieldName);

			$atts = $field->getAttributes();
			foreach ($atts as $key => $value) {
				if (!is_scalar($value)) {
					unset($atts[$key]);
				}
			}

			if ($fieldName === '__form') {
    			$this->_tmpl->addVar($args['errorTemplate'], 'ERROR_TYPE', 'form');
			} else {
    			$this->_tmpl->addVar($args['errorTemplate'], 'ERROR_TYPE', 'field');
			}

			$this->_tmpl->addVars($args['errorTemplate'], $atts, 'FIELD_');
			
			foreach ($errors as $error) {
				$error['field'] = $fieldName;
				$this->_tmpl->addVars( $args['errorTemplate'], $error, 'ERROR_' );
				$this->_tmpl->parseTemplate($args['errorTemplate'], 'a');
			}
		}

		$this->errorsRendered[$patForms->getName()] = true;
		return	true;
	}
}
?>