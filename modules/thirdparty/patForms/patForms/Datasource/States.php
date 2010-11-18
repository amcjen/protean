<?php
/**
 * Datasource for Country selectors
 *
 * This class is used to autmatically populate form elements like select boxes
 * with countries
 *
 * $Id: States.php,v 1.1 2006/04/03 20:41:04 eric Exp $
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
class patForms_Datasource_States
{
 /*   var $stateList = array(
                            'C' => array(
                            						array('value' => '', 'label' => 'Select...'),
                                        array('value' => 'AL', 'label' => 'Alabama'),
                                        array('value' => 'AK', 'label' => 'Alaska'),
                                        array('value' => 'AZ', 'label' => 'Arizona'),
                                        array('value' => 'AR', 'label' => 'Arkansas'),
                                        array('value' => 'CA', 'label' => 'California'),
                                        
                                        array('value' => 'CO', 'label' => 'Colorado'),
                                        array('value' => 'CT', 'label' => 'Connecticut'),
                                        array('value' => 'DE', 'label' => 'Delaware'),
                                        array('value' => 'DC', 'label' => 'District of Columbia'),
                                        array('value' => 'FL', 'label' => 'Florida'),
                                        array('value' => 'GA', 'label' => 'Georgia'),
                                        
                                        array('value' => 'HI', 'label' => 'Hawaii'),
                                        array('value' => 'ID', 'label' => 'Idaho'),
                                        array('value' => 'IL', 'label' => 'Illinois'),
                                        array('value' => 'IN', 'label' => 'Indiana'),
                                        array('value' => 'IA', 'label' => 'Iowa'),
                                        array('value' => 'KS', 'label' => 'Kansas'),
                                        
                                        array('value' => 'KY', 'label' => 'Kentucky'),
                                        array('value' => 'LA', 'label' => 'Louisiana'),
                                        array('value' => 'ME', 'label' => 'Maine'),
                                        array('value' => 'MD', 'label' => 'Maryland'),
                                        array('value' => 'MA', 'label' => 'Massachusetts'),
                                        array('value' => 'MI', 'label' => 'Michigan'),
                                        
                                        array('value' => 'MN', 'label' => 'Minnesota'),
                                        array('value' => 'MS', 'label' => 'Mississippi'),
                                        array('value' => 'MO', 'label' => 'Missouri'),
                                        array('value' => 'MT', 'label' => 'Montana'),    
                                        array('value' => 'NE', 'label' => 'Nebraska'),
                                        array('value' => 'NV', 'label' => 'Nevada'),
                                        
                                        array('value' => 'NH', 'label' => 'New Hampshire'),
                                        array('value' => 'NJ', 'label' => 'New Jersey'),
                                        array('value' => 'NM', 'label' => 'New Mexico'),
                                        array('value' => 'NY', 'label' => 'New York'),
                                        array('value' => 'NC', 'label' => 'North Carolina'),
                                        array('value' => 'ND', 'label' => 'North Dakota'),
                                        
                                        array('value' => 'OH', 'label' => 'Ohio'),
                                        array('value' => 'OK', 'label' => 'Oklahoma'),
                                        array('value' => 'OR', 'label' => 'Oregon'),
                                        array('value' => 'PA', 'label' => 'Pennsylvania'),
                                        array('value' => 'RI', 'label' => 'Rhode Island'),
                                        array('value' => 'SC', 'label' => 'South Carolina'),
                                        
                                        array('value' => 'SD', 'label' => 'South Dakota'),
                                        array('value' => 'TN', 'label' => 'Tennessee'),
                                        array('value' => 'TX', 'label' => 'Texas'),
                                        array('value' => 'UT', 'label' => 'Utah'),
                                        array('value' => 'VT', 'label' => 'Vermont'),
                                        array('value' => 'VA', 'label' => 'Virginia'),
                                        
                                        array('value' => 'WA', 'label' => 'Washington'),
                                        array('value' => 'WV', 'label' => 'West Virginia'),
                                        array('value' => 'WI', 'label' => 'Wisconsin'),
                                        array('value' => 'WY', 'label' => 'Wyoming')
                                    	)
                            );
		*/
		
		var $stateList = array();
           
   /**
	* Returns a list of all countries
	*
	* @access public
	* @return array The values for the patForms element
	*/
	function getValues($element) {
		
		$this->stateList = array();
	
		$c = new Criteria();
		$c->add(LocaleRegionPeer::LOCALE_COUNTRY_ID, '226');
		$c->add(LocaleRegionPeer::SHORT_NAME, '', Criteria::NOT_EQUAL);
		$c->addAscendingOrderByColumn(LocaleRegionPeer::LONG_NAME);
		$c->addAscendingOrderByColumn(LocaleRegionPeer::DISPLAY_ORDER);
		
		$results = LocaleRegionPeer::doSelect($c);
		
		foreach($results as $state) {
			$this->stateList[] = array('value' => $state->getLocaleRegionId(), 'label' => $state->getLongName());
		} 
		
		return $this->stateList;
	}
}
?>