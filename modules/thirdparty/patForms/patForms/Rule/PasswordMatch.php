<?php
/**
 * patForms Rule AnyRequired
 *
 * $Id: AnyRequired.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package        patForms
 * @subpackage    Rules
 */

/**
 * patForms Rule AnyRequired
 *
 * This rule allows you to check that passwords match
 *
 * It has to be applied after validating the form.
 *
 * @package        patForms
 * @subpackage    Rules
 * @author        Stephan Schmidt <schst@php-tools.net>
 * @license        LGPL, see license.txt for details
 * @link        http://www.php-tools.net
 */
class patForms_Rule_PasswordMatch extends patForms_Rule
{
   /**
	* define error codes and messages for the rule
	*
	* @access	private
	* @var		array	$validatorErrorCodes
    * @todo     translate error message to french
	*/
	var	$validatorErrorCodes  =   array(
		'C'	=>	array(
			1	=>	'Passwords do not match.  Please try again.',
		),
		'de' =>	array(
			1	=>	'Kennwšrter passen nicht zusammen',
		),
		'fr' =>	array(
			1	=>	'Les mots de passe ne s\'assortissent pas',
		)
	);
    
   /**
    * list of fields
    *
    * @access    private
    * @var        array
    */
    var $_password1;
    var $_password2;

   /**
    * set the names of the fields that will be required
    *
    * @access    public
    * @param    array    required fields
    */
    function setPassword1($field)
    {
        $this->_password1 = $field;
    }
    
    function setPassword2($field)
    {
        $this->_password2 = $field;
    }

   /**
    * method called by patForms or any patForms_Element to validate the
    * element or the form.
    *
    * @access    public
    * @param     object patForms    form object
    */
    function applyRule(&$form, $type = PATFORMS_RULE_AFTER_VALIDATION)
    {
        $labels = array();
        
        $el1 = &$form->getElementByName($this->_password1);
        if (patErrorManager::isError($el1)) {
					$this->addValidationError(1);
					return false;
				}
				
        $el2 = &$form->getElementByName($this->_password2);
        if (patErrorManager::isError($el2)) {
					$this->addValidationError(1);
					return false;
				}
      
        if (trim((string)$el1->getValue()) == trim((string)$el2->getValue())) {
        
        	return true;
        } else {
        
        	$this->addValidationError(1);
					return false;
        }
    }
}
?>