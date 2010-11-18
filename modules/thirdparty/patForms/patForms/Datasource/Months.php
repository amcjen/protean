<?php
/**
 * Datasource for Country selectors
 *
 * This class is used to autmatically populate form elements like select boxes
 * with countries
 *
 * $Id: Months.php,v 1.1 2006/04/03 20:41:04 eric Exp $
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
class patForms_Datasource_Months
{
    var $monthList = array(
                            'C' => array(
                            						array('value' => '', 'label' => 'Select...'),
                                        array('value' => '01', 'label' => 'January'),
                                        array('value' => '02', 'label' => 'February'),
                                        array('value' => '03', 'label' => 'March'),
                                        array('value' => '04', 'label' => 'April'),
                                        array('value' => '05', 'label' => 'May'),
                                        array('value' => '06', 'label' => 'June'),
                                        array('value' => '07', 'label' => 'July'),
                                        array('value' => '08', 'label' => 'August'),
                                        array('value' => '09', 'label' => 'September'),
                                        array('value' => '10', 'label' => 'October'),
                                        array('value' => '11', 'label' => 'November'),
                                        array('value' => '12', 'label' => 'December')
                                    	)
                            );
           
   /**
	* Returns a list of all countries
	*
	* @access public
	* @return array The values for the patForms element
	*/
	function getValues($element)
	{
		$language = $element->getLocale();
		
	    if (isset($this->monthList[$language])) {
	    	return $this->monthList[$language];
	    }
	    return $this->monthList['C'];
	}
}
?>