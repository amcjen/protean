<?php
/**
 * Datasource for patForms integration with Propel
 *
 * This class is used to autmatically populate form elements like select boxes
 * with data from tables that are related to the Propel object's table via a
 * foreign key.
 *
 * Please note: this class is currently php5 only since its solely used
 * as a base class for patForms_Definitions_Propel in order to integrate
 * patForms with Propel5 as a "rapid form solution".
 *
 * $Id: Propel.php,v 1.2 2006/05/04 08:54:02 eric Exp $
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Datasource
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 * @see         patForms_Definition
 */

/**
 * Datasource for patForms integration with Propel
 *
 * This class is used to autmatically populate form elements like select boxes
 * with data from tables that are related to the Propel object's table via a
 * foreign key.
 *
 * Please note: this class is currently php5 only since its solely used
 * as a base class for patForms_Definitions_Propel in order to integrate
 * patForms with Propel5 as a "rapid form solution".
 *
 * @author		Sven Fuchs <svenfuchs@artweb-design.de>
 * @package		patForms
 * @subpackage	Datasource
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 * @see         patForms_Definition
 */
class patForms_Datasource_Propel
{
   /**
	* The Propel peers classname
	*
	* @var      string
	* @access	private
	*/
	private $peername;

   /**
	* The Propel objects classname
	*
	* @var      string
	* @access	private
	*/
	private $classname;

   /**
	* Config for the form elements label creation from Propel data
	*
	* @var      array
	* @access	private
	* @see		patForms_Datasource_Propel::getValues()
	*/
	private $label;

   /**
	* Config for the form elements value creation from Propel data
	*
	* @var      array
	* @access	private
	* @see		patForms_Datasource_Propel::getValues()
	*/
	private $value;
	
	/**
	 * Criteria class instance, for overriding propel criteria
	 */
	private $c;

	public function __construct($conf=NULL) {
	
		$this->c = NULL;
		if ($conf != NULL) {
			$this->init($conf);
		}
	}
	
	public function init($conf)
	{
		$this->peername = $conf['peername'];
		$this->label = $conf['label'];
		$this->value = $conf['value'];

		$classname = call_user_func(array($this->peername, 'getOMClass'));
		$this->classname = array_pop($tmp = explode('.', $classname));
	}
	
	public function setCriteria($c) {
	
		$this->c = clone $c;
	}

	/**
	 * Returns values for population of the patForms form element (e.g. a select box)
	 *
	 * This method will lookup, format and return data from the Propels object
	 * peer as defined by the members $label and $conf.
	 *
	 * E.g. an array like this:
	 *
	 *     array(
	 *         'initial' => 'Please select one...'
	 *         'members' => array('Id'),
	 *         'mask' => '%s'
	 *     );
	 *
	 * would build values or labels populated from the Id field of the Propels
	 * object peer and simply display these values. Addionally it would add the
	 * string 'Please select one...' as the first label to the select box.
	 *
	 * An array like this:
	 *
	 *     array(
	 *         'initial' => 'Please select one...'
	 *         'members' => array('LastName', 'FirstName'),
	 *         'mask' => '%s (%s)'
	 *     );
	 *
	 * would select the fields Lastname and Firstname and display them with the
	 * format of "LastName (FirstName)".
	 *
	 * @access public
	 * @return array The values for the patForms element
	 */
	public function getValues()
	{
		$map = call_user_func(array($this->peername, 'getTableMap'));

		if ($this->c == NULL) {
			$c = new Criteria();
		} else {
			$c = clone $this->c;
		}
	
		$c->clearSelectColumns();
	
		foreach (array($this->label, $this->value) as $arr) {
			foreach ($arr['members'] as $member) {

				if (is_array($member)) {
					foreach ($member as $member) {
						$c->addSelectColumn(constant($this->peername . '::' . $map->getColumnByPhpName($member)->getName()));
					}
				} else {
					$c->addSelectColumn(constant($this->peername . '::' . $map->getColumnByPhpName($member)->getName()));
				}
			}
		}

		if (isset($this->label['initial']) OR isset($this->value['initial'])) {
			$label = isset($this->label['initial']) ? $this->label['initial'] : '';
			$value = isset($this->value['initial']) ? $this->value['initial'] : '';
			$result[] = array(
				'value' => $value,
				'label' => $label
			);
		}

		if ($this->c == NULL) {
			$c = new Criteria();
		} else {
			$c = clone $this->c;
		}
		
		$results = call_user_func(array($this->peername, 'doSelect'), $c);
		foreach($results as $object) {
			foreach (array('label', 'value') as $key) {
				$arr = $this->$key;
				$params = array($arr['mask']);
				foreach ($arr['members'] as $member) {
					if (is_array($member)) {
						foreach ($member as $member) {
							$params[] = $object->getByName($member);
						}
					} else {
						$params[] = $object->getByName($member);
					}
				}
				$$key = call_user_func_array('sprintf', $params);
				$tmp[$key] = $$key;
			}
			$result[] = $tmp;
		}

		return $result;
	}
}
?>