<?php
/**
 * patForms Renderer for Smarty
 *
 * $Id: Smarty.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package       patForms
 * @subpackage    Renderer
 */

/**
 * Error: Smarty is not available
 */
define('PATFORMS_RENDERER_SMARTY_ERROR_NO_CLASS', 'Renderer:Smarty:001');


/**
 * patForms Renderer for Smarty
 *
 * This requires Smarty to work. See http://smarty.php.net/
 *
 * @package       patForms
 * @subpackage    Renderer
 * @author        Sven Fuchs <svenfuchs@artweb-design.de>
 * @license       LGPL, see license.txt for details
 * @link          http://www.php-tools.net
 */
class patForms_Renderer_Smarty extends patForms_Renderer
{
   /**
    * Stores forms data
    *
    * @access    private
    * @var       array
    */
    var $forms = array();

   /**
    * Stores the template object
    *
    * @access    private
    * @var       object Smarty
    */
    var $_tmpl = null;

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
        'display',
    );

   /**
    * Sets the Smarty object, that will be used to render
    * the page.
    *
    * @access    public
    * @param    object Smarty
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
    * Serializes the form elements and sets them to a Smarty
    * object.
    *
    * Arguments that can be passed:
    * - template = an instance of Smarty
    * - tmplDir  = directory in which templates are stored
    * - compileDir = directory in which templates are compiled
    *
    * @access   public
    * @param    object  Reference to the patForms object
    * @param    array   optional arguments for the renderer
    * @return   object 	Smarty
    */
    function &render(&$patForms, $args = array())
    {
		if (is_object($args)) {
			$args = array('template' => $args);
		}

        // set the template
        if (isset($args['template'])) {
            $this->_tmpl = $args['template'];
        }

        // create a new template instance
        if ($this->_tmpl === null) {
            if (!class_exists('Smarty')) {
                return patErrorManager::raiseError(PATFORMS_RENDERER_SMARTY_ERROR_NO_CLASS, 'No instance of Smarty has been passed, nor has the class been loaded.');
            }
            $this->_tmpl = new Smarty();
        }

        // set the base directory
        if (isset($args['tmplDir'])) {
            $this->_tmpl->template_dir = $args['tmplDir'];
        }

        // set the base directory
        if (isset($args['compileDir'])) {
            $this->_tmpl->compile_dir = $args['compileDir'];
        }

		// render start and end tags
		$data = array(
			'start' => $patForms->serializeStart(),
			'end' => $patForms->serializeEnd(),
			'elements' => array()
		);

		// render the elements
		foreach ($patForms->getElements() as $element) {

			$ptr = &$data['elements'][$element->getName()];
			$ptr['tag'] = $element->serialize();

			foreach ($this->_attributes as $name) {
				$ptr[$name] = (string) $element->getAttribute($name);
			}
		}

		// render errors if present
    	if ($validationErrors = $patForms->getValidationErrors()) {
			foreach($validationErrors as $name => $errors) {
				if (empty($errors)) {
					continue;
				}

				foreach($errors as $error) {
					$data['errors'][$name] = $error;
					$data['errors'][$name]['field'] = $name;
					$data['errors'][$name]['isFormError'] = ($name == '__form');
				}
			}
		}

		$this->forms[$patForms->getName()] = $data;
		$this->_tmpl->assign('forms', $this->forms);

        return $this->_tmpl;
    }
}
?>