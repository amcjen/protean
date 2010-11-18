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
 * This rule allows you to set a list of elements which
 * will be checked, if any of them has been set.
 *
 * It has to be applied after validating the form.
 *
 * @package        patForms
 * @subpackage    Rules
 * @author        Stephan Schmidt <schst@php-tools.net>
 * @license        LGPL, see license.txt for details
 * @link        http://www.php-tools.net
 */
class patForms_Rule_AnyRequired extends patForms_Rule
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
			1	=>	'Please fill out at least one of these fields: [FIELD_LABELS]',
		),
		'de' =>	array(
			1	=>	'Bitte füllen Sie zumindest eines dieser Felder aus: [FIELD_LABELS]',
		),
		'fr' =>	array(
			1	=>	'Veuillez remplir au moins un des champs suivants: [FIELD_LABELS]',
		)
	);
    
   /**
    * list of fields
    *
    * @access    private
    * @var        array
    */
    var $_fieldNames = array();

   /**
    * set the names of the fields that will be required
    *
    * @access    public
    * @param    array    required fields
    */
    function setFieldNames($fields)
    {
        $this->_fieldNames = $fields;
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
        foreach ($this->_fieldNames as $fieldName) {
        	$el = &$form->getElementByName($fieldName);
        	if (patErrorManager::isError($el)) {
        		continue;
        	}
        	if ((string)$el->getValue() === '') {
        	    array_push($labels, $el->getAttribute('label'));
        		continue;
        	}
        	return true;
        }

        $vars = array(
                    'field_labels' => implode(', ', $labels)
                );
		$this->addValidationError(1, $vars);
		return false;
    }
}
?>