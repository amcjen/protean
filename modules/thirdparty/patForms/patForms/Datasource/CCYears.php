<?php
/**
 * Datasource for Country selectors
 *
 * This class is used to autmatically populate form elements like select boxes
 * with countries
 *
 * $Id: CCYears.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @package		patForms
 * @subpackage	Datasource
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */

/**
 * Datasource for Country selectors
 *
 * This class is used to autmatically populate form elements like select boxes
 * with countries
 *
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @package		patForms
 * @subpackage	Datasource
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */
class patForms_Datasource_CCYears
{
    var $yearList = array(
                            'C' => array(
                            						array('value' => '', 'label' => 'Select...')
                            						)
                            );
           
  function __construct() {
  	
  	$year = date('Y');
  	
  	for ($i=0; $i<10; $i++) {
  	
  		$this->yearList['C'][] = array('value' => $year + $i, 'label' => $year + $i);
  	}
  }
  
   /**
	* Returns a list of all countries
	*
	* @access public
	* @return array The values for the patForms element
	*/
	function getValues($element)
	{
		$language = $element->getLocale();
		
	    if (isset($this->yearList[$language])) {
	    	return $this->yearList[$language];
	    }
	    return $this->yearList['C'];
	}
}
?>