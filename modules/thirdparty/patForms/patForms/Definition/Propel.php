<?php
/**
 * patForms_Definition for Propel integration
 *
 * This class maps Creole types and Propel Validators to patForms_Elements
 * and patForms_Rules. The class will automatically check for the existence
 * of a patForms_Definition xml file and populate itself from the file if it'S
 * present. If it's not present the class will lookup necessary data in the
 * Propel peer class and write it to the xml file.
 *
 * This way the xml file will be created on the fly when the form definition
 * is created for the first time. You can then tweak this file to match your
 * needs. E.g. you'll want to change labels, descriptions, element types or
 * the data fields that are used to populate select boxes for related tables
 * (foreign keys).
 *
 * Please note: this class is currently php5 only since its solely used
 * as a base class for patForms_Definitions_Propel in order to integrate
 * patForms with Propel5 as a "rapid form solution".
 *
 * $Id: Propel.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Definition
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */

/**
 * requires the base class
 */

require_once PATFORMS_INCLUDE_PATH . '/Definition.php';

/**
 * requires the creole types
 */

require_once 'creole/CreoleTypes.php';

/**
 * patForms_Definition for Propel integration
 *
 * This class maps Creole types and Propel Validators to patForms_Elements
 * and patForms_Rules. The class will automatically check for the existence
 * of a patForms_Definition xml file and populate itself from the file if it
 * exists. If it does not exist the class will lookup necessary data in the
 * Propel peer class and write it to the xml file.
 *
 * This way the xml file will be created on the fly when the form definition
 * is created for the first time. You can then tweak this file to match your
 * needs. E.g. you'll want to change labels, descriptions, element types or
 * the data fields that are used to populate select boxes for related tables
 * (foreign keys).
 *
 * Please note: this class is currently php5 only since its solely used
 * as a base class for patForms_Definitions_Propel in order to integrate
 * patForms with Propel5 as a "rapid form solution".
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Definition
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */
class patForms_Definition_Propel extends patForms_Definition
{
   /**
	* Map Creole data types to patForm_Element types
	*
	* @static
	* @access	private
	*/
	private static $creoleTypeMap = array(
		CreoleTypes::BOOLEAN    	=> 'Switch',	// BOOLEAN 			= 1;
		CreoleTypes::BIGINT     	=> 'Number',	// BIGINT 			= 2;
		CreoleTypes::SMALLINT   	=> 'Number',	// SMALLINT 		= 3;
		CreoleTypes::TINYINT    	=> 'Number',	// TINYINT 			= 4;
		CreoleTypes::INTEGER    	=> 'Number',	// INTEGER 			= 5;
		CreoleTypes::CHAR       	=> 'String',	// CHAR 			= 6;
		CreoleTypes::VARCHAR    	=> 'String',	// VARCHAR 			= 7;
		CreoleTypes::FLOAT      	=> 'Number',	// FLOAT 			= 8;
		CreoleTypes::DOUBLE     	=> 'Number',	// DOUBLE 			= 9;
		CreoleTypes::DATE       	=> 'Date',		// DATE 			= 10;
		CreoleTypes::TIME       	=> 'Date',		// TIME 			= 11;
		CreoleTypes::TIMESTAMP  	=> 'Date',		// TIMESTAMP 		= 12;
		CreoleTypes::VARBINARY  	=> 'String',	// VARBINARY 		= 13;
		CreoleTypes::NUMERIC    	=> 'Number',	// NUMERIC 			= 14;
		CreoleTypes::BLOB       	=> 'Text',		// BLOB 			= 15;
		CreoleTypes::CLOB       	=> 'Text',		// CLOB 			= 16;
		CreoleTypes::TEXT       	=> 'Text',		// TEXT 			= 17;
		CreoleTypes::LONGVARCHAR	=> 'Text',		// LONGVARCHAR 		= 17;
		CreoleTypes::DECIMAL    	=> 'Number',	// DECIMAL 			= 18;
		CreoleTypes::REAL       	=> 'Number',	// REAL 			= 19;
		CreoleTypes::BINARY     	=> 'String',	// BINARY 			= 20;
		CreoleTypes::LONGVARBINARY	=> 'Text',		// LONGVARBINARY 	= 21;
		CreoleTypes::YEAR       	=> 'Date',		// YEAR 			= 22;
		CreoleTypes::ARR   	    	=> 'String',
		CreoleTypes::OTHER      	=> 'String'
	);

   /**
	* Map Propel validator types to patForm_Rule classnames
	*
	* @static
	* @access	private
	*/
	private static $validatorTypeMap = array(
		'unique' 		=> null,
		'minLength' 	=> 'MinLength',
		'maxLength' 	=> 'MaxLength',
		'minValue' 		=> 'MinValue',
		'maxValue' 		=> 'MaxValue',
		'match'			=> 'Match',
		'notMatch'		=> 'NotMatch',
		'required' 		=> null, // will be done by the elements "required" attribute
		'validValues'	=> 'ValidValues',
	);

	/**
	 * Factory method to create a new patForms_Definition_Propel instance
	 *
	 * This method will check for the existence of a patForms_Definition xml
	 * file and populate itself from the file if it exists. If it does not
	 * exist the class will lookup necessary data in the Propel peer class
	 * and write it to the xml file.
	 *
	 * @static
	 * @access public
	 * @param  string  $conf 	the name of the Propel class (e.g. 'Book') OR
	 *						    an assoc array of parameters:
	 *                          'name' => 'Book', // Propel object classname
	 *                          'filename'  => 'form.book.xml', // filename for xml definition
	 * 							'autoValidate' => 'save' // patForms autoValidate option
	 * @return object The patForms_Definition_Propel instance
	 * @todo   Probably check the mtime of the Propel basepeer class and
	 *         compare it with the mtime of the xml file if a parameter
	 *         pathToPropelBasepeer is provided. Defaults to: no check.
	 */
	static public function create($conf)
	{
		if (!is_array($conf)) {
			$conf = array(
				'name' => $conf,
				'filename' => 'form.' . $conf . '.xml',
			);
		}
		extract($conf);

		$conf['autoValidate'] = isset($autoValidate) ? $autoValidate : 'save';

		$definition = new patForms_Definition_Propel($name, $conf);

		if (file_exists($filename)) {
			// load definition from xml file
			$definition->load($filename);
		} else {
			// populate definition from table map and save it to xml file
			$definition = self::populateFromPropel($definition, $conf);
			$definition->save($filename);
		}

		return $definition;
	}

	/**
	 * Populates the definition with the properties from a Propel peer
	 *
	 * @access private
	 * @param  object $definition A patForms_Definition instance
	 * @param  array  $conf       An assoc array of parameters:
	 *                            'name' => 'book', // Propel object classname
	 * @return object The patForms_Definition instance
	 * @todo   Probably add an option to have "namespaced" request vars once
	 *         this feature is implemented in patForms
	 * @todo   Date type for patForms_Element_Date does not work
	 * @todo   Retrieve more specific data for the element attributes
	 */
	private function populateFromPropel($definition, $conf)
	{
		extract($conf);

		$dbname = constant($name . 'Peer::DATABASE_NAME');
		$tablename = constant($name . 'Peer::TABLE_NAME');

		$dbMap = Propel::getDatabaseMap($dbname);
		$tableMap = $dbMap->getTable($tablename);
		$cols = $tableMap->getColumns();

		foreach($cols as $col) {

			$elementName = $col->getPhpName();

			if ($col->isPrimaryKey()) {
				$elementType = 'Hidden';
			} else {
				$elementType = self::$creoleTypeMap[$col->getCreoleType()];
			}

			$attributes = self::getAttributesFor($elementType, $elementName);

			switch ($col->getCreoleType()) {
				case CreoleTypes::BOOLEAN: {
					$attributes['value'] = 1;
					break;
				}
				case CreoleTypes::DATE: {
					$attributes['dateformat'] = 'Y-m-d';
					$attributes['presets'] = 'no';
					break;
				}
				case CreoleTypes::TIME: {
					$attributes['dateformat'] = 'Y-m-d';
					$attributes['presets'] = 'no';
					break;
				}
				case CreoleTypes::TIMESTAMP: {
					$attributes['dateformat'] = 'YmdHis';
					$attributes['presets'] = 'no';
					break;
				}
				case CreoleTypes::YEAR: {
					$attributes['dateformat'] = 'Y';
					$attributes['presets'] = 'no';
					break;
				}
			}

			if($col->isForeignKey()) {

				$relColname = $col->getRelatedColumnName();
				$relTablename = $col->getRelatedTableName();

				$tableMap = $dbMap->getTable($relTablename);
				$relClassname = $tableMap->getPhpName();
				$relColPhpname = $tableMap->getColumn($relColname)->getPhpname();

				$attributes['datasource'] = array (
					'type' => 'Propel',
					'peername' => $relTablename . 'Peer',
					'label' => array(
						'initial' => 'Please select one...',
						'members' => array($relColPhpname),
						'mask' => '%s',
					),
					'value' => array(
						'members' => array($relColPhpname),
						'mask' => '%s',
					),
				);
				$elementType = 'Enum';
			}

			$rules = array();
			if($col->hasValidators()) {
				foreach ($col->getValidators() as $validator) {
					$validatorName = $validator->getName();
					$validatorType = self::$validatorTypeMap[$validatorName];
					if (!is_null($validatorType)) {
						$rules[$validatorName] = array (
							'table' => $col->getTablename(),
							'col' => $col->getColumnName(),
							'name' => $validatorName,
							'type' => self::$validatorTypeMap[$validatorName],
							'value' => $validator->getValue(),
							'class' => $validator->getClass(),
							'message' => $validator->getMessage(),
						);
					}
				}
			}

			$definition->addElement($elementName, $elementType, $attributes, $rules);
		}

		return $definition;
	}

	static private function getAttributesFor($type, $name) {

		$attributes = array('name'  => $name);
		$defaultAttributes = self::getElementDefaultAttributes($type);

		if ($type !== 'Hidden') {
			$attributes['label'] = $name;
			$attributes['title'] = $name;
		}

		if (self::hasDefaultAttribute('edit', $type)) {
			$attributes['edit'] = $defaultAttributes['edit'];
		}

		if (self::hasDefaultAttribute('display', $type)) {
			$attributes['display'] = 'yes';
		}

		// TODO Can we retrieve this info from the Column object?
		if (self::hasDefaultAttribute('required', $type)) {
			$attributes['required'] = 'yes';
		}

		return $attributes;
	}

	static private function hasDefaultAttribute($name, $type) {

		return in_array($name, array_keys(self::getElementDefaultAttributes($type)));
	}

	static private function getElementDefaultAttributes($type) {

		static $elements = array();

		if (!isset($elements[$type])) {
			$elements[$type] = patForms::createElement('', $type, null);
		}

		return $elements[$type]->getAttributes();
	}
}

?>