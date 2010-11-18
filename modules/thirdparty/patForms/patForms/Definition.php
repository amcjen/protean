<?php
/**
 * Base class for xml based Definitions of a patForms instance
 *
 * A patForms_Definition can be written to and populated from an xml file.
 * It can be used to populate a patForms instance with form elements, rules
 * and other properties.
 *
 * Please note: this class is currently php5 only since its solely used
 * as a base class for patForms_Definitions_Propel in order to integrate
 * patForms with Propel5 as a "rapid form solution". This might change in
 * future.
 *
 * This requires the PEAR XML_Serializer package to be installed and
 * available in the include_path
 *
 * $Id: Definition.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Definition
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 * @see         patForms_Creator_Definition
 */

/**
 * Base class for xml based Definitions of a patForms instance
 *
 * A patForms_Definition can be written to and populated from an xml file.
 * It can be used to populate a patForms instance with form elements, rules
 * and other properties.
 *
 * Please note: this class is currently php5 only since its solely used
 * as a base class for patForms_Definitions_Propel in order to integrate
 * patForms with Propel5 as a "rapid form solution". This might change in
 * future.
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Definition
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 * @see         patForms_Creator_Definition
 */
class patForms_Definition {

   /**
	* Stores the form name
	*
	* @var      string
	* @access	private
	*/
	private $name = '';

   /**
	* Stores the form's autoValidate attribute
	*
	* @var      boolean
	* @access	private
	*/
	private $autoValidate = false;

   /**
	* Stores the form element definitions
	*
	* @var      array
	* @access	private
	*/
	private $elements = array();

   /**
	* The constructor
	*
	* Adds a timestamp as mtime to the definition's data, that might be
	* overwritten by loading a definition file
	*
	* @access	public
	* @param    string   $name           The name of the form
	* @param    string   $autoValidate   The autoValidate property of the form
	*/
	public function __construct($name, $attributes = array())
	{
		$this->name = $name;

		if (isset($attributes['autoValidate'])) {
			$this->autoValidate = $attributes['autoValidate'];
		}
	}

   /**
	* Factory method to create a new patForms_Definition instance
	*
	* not implemented
	*
	* @static
	* @access	public
	* @param	array    $conf   Data needed by the factory method
	*/
	static public function create($conf)
	{
	}

   /**
	* Returns the form name
	*
	* @access	public
	* @return   string  The form name
	*/
	public function getName()
	{
		return $this->name;
	}

   /**
	* Returns the form's autoValidate attribute
	*
	* @access	public
	* @return   boolean  the form's autoValidate attribute
	*/
	public function getAutoValidate()
	{
		return $this->autoValidate;
	}

   /**
	* Returns the form element definitions
	*
	* @access	public
	* @return   array  The form element definitions
	*/
	public function getElements()
	{
		return $this->elements;
	}

   /**
	* Adds the definition for a form element
	*
	* @access	public
	* @param	string   $name   The name of the element
	* @param	string   $type   The type of the element
	* @param	array    $attributes   Attributes for the element
	* @param	array    $rules  Definitions for patForm_Rules
	* @todo     Change protocol to addElement(array $data) to be more flexible
	*/
	public function addElement($name, $type, $attributes = array(), $rules = array())
	{
		if (is_array($type)) {
			extract($type);
		}

		$this->elements[$name] = array(
			'name' => $name,
			'type' => $type
		);

		foreach ($attributes as $key => $value) {
			$value = $this->cast($value);
			$this->elements[$name]['attributes'][$key] = $value;
		}
		foreach ($rules as $key => $rule) {
			$this->elements[$name]['rules'][$key] = $rule;
		}
	}

   /**
	* Loads the definitions properties from an xml file
	*
	* @access	public
	* @param	string   $filename   The filename of the xml file
	*/
	public function load($filename)
	{
		$data = $this->read($filename);

		foreach ($data as $key => $value) {
			if ($key == 'elements') {
				foreach ($value as $name => $element) {
					$this->addElement($name, $element);
				}
			} else {
				$this->data[$key] = $this->cast($value);
			}
		}
	}

   /**
	* Writes the definitions properties to an xml file
	*
	* @access	public
	* @param	string   $filename   The filename of the xml file
	*/
	public function save($filename)
	{
		$this->write($filename, array (
			'name' => $this->name,
			'autoValidate' => $this->autoValidate,
			'elements' => $this->elements,
		));
	}

   /**
	* Reads an xml file
	*
	* @access	protected
	* @param	string   $filename   The filename of the xml file
	* @return   array    The data read from the file
	*/
	protected function read($filename)
	{
		require_once "XML/Unserializer.php";

		$xml = file_get_contents($filename);
		$unserializer = new XML_Unserializer();
		$unserializer->unserialize($xml);
		return $unserializer->getUnserializedData();
	}

   /**
	* Writes data to an xml file
	*
	* @access	protected
	* @param	string   $filename   The filename of the xml file
	* @param	array    $data       The data to write
	*/
	protected function write($filename, $data)
	{
		require_once "XML/Serializer.php";

		$serializer = new XML_Serializer(array (
			'addDecl' => true,
			'encoding' => 'ISO-8859-1',
			'indent' => '  ',
			'rootName' => 'form',
			'defaultTagName' => 'tag'
		));
		$serializer->serialize($data);
		$xml = $serializer->getSerializedData();

		$fp = fopen($filename, 'w+');
		fputs($fp, $xml);
		fclose($fp);
	}

   /**
	* Tries to guess the correct datatype for a variable and casts it if
	* necessary
	*
	* Please note: this doesn't seem to work right now with patForms_Elements
	* and will therefor return the value parameter without changing anything
	* for now. This may change in future.
	*
	* @access	protected
	* @param	string   $value     The value to check
	* @return   array    The value (probably cast to another type)
	*/
	protected function cast($value)
	{
		return $value;

		// seems as if patForms_Element(s) are broken here
		// e.g. in patForms_Element_Text::serializeHtmlDefault()
		// at line 245 if( $this->attributes['display'] == 'no' )
		// will result to true if the display attribute is set
		// to (php boolean) true
		// so casting the 'true'/'false' and 'yes'/'no' values
		// would break intended behaviour here

		if (is_array($value) OR is_bool($value)) {
			return $value;
		}
		if ($value === 'true') {
			return true;
		}
		if ($value === 'false') {
			return false;
		}
		if (preg_match('/^[+-]?[0-9]+$/', $value)) {
			settype($value, 'int');
			return $value;
		}
		if (preg_match('/^[+-]?[0-9]*\.[0-9]+$/', $value)) {
			settype($value, 'double');
			return $value;
		}
		return $value;
	}
}


?>