<?php
/**
 * patForms collection
 *
 * $Id: Collection.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Rules
 */

/**
 * patForms collection
 *
 * This is used as a container for several patForms objects.
 *
 * @access		protected
 * @package		patForms
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Collection
{
   /**
	* forms in the collection
	*
	* @access	public
	* @var		array
	*/
	var	$forms = array();

   /**
	* create a new collection object
	*
	* @access	public
	*/
	function patForms_Collection()
	{
	}

   /**
	* add a new form to the collection
	*
	* @access	public
	* @param	object patForms
	*/
	function addForm(&$form)
	{
		$name = &$form->getName();
		$this->forms[$name] = &$form;
	}

   /**
	* check, whether the collection contains a form
	*
	* @access	public
	* @param	string				name of the form
	* @return	boolean
	*/
	function containsForm($name)
	{
		if (isset($this->forms[$name])) {
			return true;
		}
		return false;
	}

   /**
	* get a form from the collection
	*
	* @access	public
	* @param	string				name of the form
	* @return	object patForms		form object
	*/
	function &getForm($name)
	{
		if (isset($this->forms[$name])) {
			return $this->forms[$name];
		}
		$null = null;
		return $null;
	}
	
   /**
    * sets a renderer object that will be used to render
	* the form.
	*
	* @access	public
	* @param	object		&$renderer	The renderer object
	* @return	mixed		$success	True on success, patError object otherwise.
	* @see		patForms::createRenderer()
	* @uses		patForms::renderForm()
	*/
	function setRenderer(&$renderer, $args = array())
	{
		if (!is_object($renderer)) {
			return patErrorManager::raiseError( 
				PATFORMS_ERROR_INVALID_RENDERER, 
				'You can only set a patForms_Renderer object with the setRenderer() method, "'.gettype( $renderer ).'" given.'
			);
		}
		
		foreach (array_keys($this->forms) as $formName) {
			$this->forms[$formName]->setRenderer($renderer, $args);
		}
		return true;
	}
	
   /**
	* render all forms in the collection
	*
	* @access public
	* @param  mixed		any arguments that should be passed to the renderer
	*/
	function renderForm($args = null)
	{
		foreach (array_keys($this->forms) as $formName) {
			$this->forms[$formName]->renderForm($args);
		}
		return true;
	}

   /**
    * Validate all forms in the collection
    *
	* @access	public
    * @param    boolean     Flag to indicate, whether the forms should be validated again, if they already have been validated.
	* @return	boolean	    True if all forms could be validated, false otherwise.
    */
    function validateForm($revalidate = false) {
        $result = true;
		foreach (array_keys($this->forms) as $formName) {
			$tmp = $this->forms[$formName]->validateForm($revalidate);
			$result = $result & $tmp;
		}
		return $result;
    }

   /**
	* Finalize all forms
	*
	* @access	public
	* @return	bool	$success	Wether all elements could be finalized
	*/
	function finalizeForm()
	{
        $result = true;
		foreach (array_keys($this->forms) as $formName) {
			$tmp = $this->forms[$formName]->finalizeForm();
			$result = $result & $tmp;
		}
		return $result;
	}
	
   /**
    * Get an element from any form
    *
    * If more than one form contains an element of the specified name,
    * only the first will be returned.
    *
    * @param    string
    * @return   mixed
    */
	function &getElementByName($name)
	{
		if ($name == '__form') {
			return $this;
		}
		foreach (array_keys($this->forms) as $formName) {
		    patErrorManager::pushExpect(PATFORMS_ERROR_ELEMENT_NOT_FOUND);
			$element = $this->forms[$formName]->getElementByName($name);
		    patErrorManager::popExpect(PATFORMS_ERROR_ELEMENT_NOT_FOUND);
			if (!patErrorManager::isError($element)) {
			    return $element;
			}
		}

		$error = &patErrorManager::raiseError(
			PATFORMS_ERROR_ELEMENT_NOT_FOUND,
			'Element '.$name.' could not be found.'
		);
		return $error;
	}

   /**
    * Get an element from any form
    *
    * If more than one form contains an element with the specified id,
    * only the first will be returned.
    *
    * @param    string
    * @return   mixed
    */
	function &getElementById($id)
	{
		foreach (array_keys($this->forms) as $formName) {
		    patErrorManager::pushExpect(PATFORMS_ERROR_ELEMENT_NOT_FOUND);
			$element = $this->forms[$formName]->getElementById($id);
		    patErrorManager::popExpect(PATFORMS_ERROR_ELEMENT_NOT_FOUND);
			if (!patErrorManager::isError($element)) {
			    return $element;
			}
		}

		$error = &patErrorManager::raiseError(
			PATFORMS_ERROR_ELEMENT_NOT_FOUND,
			'Element '.$name.' could not be found.'
		);
		return $error;
	}
}
?>