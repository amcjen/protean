<?php
/**
 * Creator for patForms instance creation from a patForms_Definition object
 *
 * This class creates a patForms instance and populates it with form elements,
 * rules and other properties as defined by the provided patForms_Definition
 * object.
 *
 * Please note: this class is currently php5 only since its solely used
 * as a base class for patForms_Definitions_Propel in order to integrate
 * patForms with Propel5 as a "rapid form solution".
 *
 * $Id: Definition.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Creator
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 * @see         patForms_Definition
 */

/**
 * Creator for patForms instance creation from a patForms_Definition object
 *
 * This class creates a patForms instance and populates it with form elements,
 * rules and other properties as defined by the provided patForms_Definition
 * object.
 *
 * Please note: this class is currently php5 only since its solely used
 * as a base class for patForms_Definitions_Propel in order to integrate
 * patForms with Propel5 as a "rapid form solution".
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Creator
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 * @see         patForms_Definition
 */

class patForms_Creator_Definition
{
	/**
	 * Factory method to create a new patForms instance
	 *
	 * This method will check for the existence of a patForms_Definition xml
	 * file and populate itself from the file if it exists. If it does not
	 * exist the class will lookup necessary data in the Propel peer class
	 * and write it to the xml file.
	 *
	 * When a Propel object is provided as a parameter, the form values will
	 * be populated with the objects member values.
	 *
	 * @static
	 * @access public
	 * @param  object  $definition  A patForms_Definition instance
	 * @param  object  $object      A Propel object instance
	 * @return object The patForms instance
	 */
	static function create($definition, $object = null)
	{
		$form = patForms::createForm(null, array(
			'name' => $definition->getName()
		));

		foreach ($definition->getElements() as $el) {

			if (!empty($el['attributes']['datasource'])) {
				$datasource = $el['attributes']['datasource'];
				unset($el['attributes']['datasource']);
			} else {
				$datasource = null;
			}

			$element = &$form->createElement($el['name'], $el['type'], $el['attributes']);

			if (!is_null($datasource)) {
				$type = $datasource['type'];
				$ds = patForms::createDatasource($type);
				$ds->init($datasource);
				$element->setDatasource($ds);
			}
			if (isset($el['rules'])) {
				foreach($el['rules'] as $rule) {
					$type = $rule['type'];
					$value = $rule['value'];
					$rule = patForms::createRule($type);
					$rule->setValue($value);
					$element->addRule($rule);
				}
			}
			$form->addElement($element);
		}
		if (!is_null($object)) {
			$form->setValues($object->toArray());
		}
		$form->setAutoValidate($definition->getAutoValidate());

		return	$form;
	}

}
?>