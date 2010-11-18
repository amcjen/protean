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
class patForms_Rule_CCExpirationDate extends patForms_Rule
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
			1	=>	'Credit card expiration date must be current',
		)
	);
    
   /**
    * list of fields
    *
    * @access    private
    * @var        array
    */
    var $_month;
    var $_year;

   /**
    * set the names of the fields that will be required
    *
    * @access    public
    * @param    array    required fields
    */
    function setMonth($field)
    {
        $this->_month = $field;
    }
    
    function setYear($field)
    {
        $this->_year = $field;
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
        $el1 = &$form->getElementByName($this->_month);
        if (patErrorManager::isError($el1)) {
					$this->addValidationError(1);
					return false;
				}
				
        $el2 = &$form->getElementByName($this->_year);
        if (patErrorManager::isError($el2)) {
					$this->addValidationError(1);
					return false;
				}
       
       
       	$ccDate = mktime(0,0,0,$el1->getValue()+1, 0, $el2->getValue());
       
        if (mktime() < $ccDate) {
        
        	return true;
        } else {
        
        	$this->addValidationError(1);
					return false;
        }
    }
}
?>