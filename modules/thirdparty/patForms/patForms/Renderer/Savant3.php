<?php
/**
 * patForms Renderer for Savant3
 *
 * $Id: Savant3.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package       patForms
 * @subpackage    Renderer
 */

/**
 * Error: Savant3 is not available
 */
define('PATFORMS_RENDERER_SAVANT3_ERROR_NO_CLASS', 'Renderer:Savant3:001');


/**
 * patForms Renderer for Savant3
 *
 * This requires Savant3 to work. See http://www.phpsavant.com
 *
 * @package       patForms
 * @subpackage    Renderer
 * @author        Sven Fuchs <svenfuchs@artweb-design.de>
 * @license       LGPL, see license.txt for details
 * @link          http://www.php-tools.net
 */
class patForms_Renderer_Savant3 extends patForms_Renderer
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
    * @var       object Savant3
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
    * Sets the Savant3 object, that will be used to render
    * the page.
    *
    * @access    public
    * @param    object Savant3
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
    * Serializes the form elements and sets them to a Savant3
    * object.
    *
    * Arguments that can be passed:
    * - template = an instance of Savant3
    * - tmplFile = name of the template file
    * - tmplDir  = directory in which templates are stored
    *
    * @access   public
    * @param    object  Reference to the patForms object
    * @param    array   optional arguments for the renderer
    * @return   object 	Savant3
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
            if (!class_exists('Savant3')) {
                return patErrorManager::raiseError(PATFORMS_RENDERER_SAVANT3_ERROR_NO_CLASS, 'No instance of Savant3 has been passed, nor has the class been loaded.');
            }
            $this->_tmpl = new Savant3();
        }

        // set the base directory
        if (isset($args['tmplDir'])) {
            $this->_tmpl->setPath('template', $args['tmplDir']);
        }

        // load the template
        if (isset($args['tmplFile'])) {
            $result = $this->_tmpl->setTemplate($args['tmplFile']);
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