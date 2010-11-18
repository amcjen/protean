<?php
/**
 * patForms Date subelement Meridiem
 *
 * Handles year input and validation for the date element
 * 
 * $Id: Meridiem.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 */

/**
 * Notice: the meridiem can only be used with the 12-hour date format.
 */
 define( 'PATFORMS_ELEMENT_DATE_ELEMENT_MERIDIEM_NOTICE_NOT_VALID_HERE', 'patForms:Element:Date:Element:Meridiem:01' );

/**
 * patForms Date subelement Meridiem
 *
 * Handles year input and validation for the date element
 * 
 * $Id: Meridiem.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 */
class patForms_Element_Date_Element_Meridiem extends patForms_Element_Date_Element
{
   /**
	* Stores all needed token configurations, i.e. the length and output
	* format of the corresponding values.
	* 
	* @access	private
	* @var		array
	*/
	var $tokens = array(
		'a' => array(
			'length'	=>	2,
			'format'	=>	'%s',
		),
		'A' => array(
			'length'	=>	2,
			'format'	=>	'%s',
		),
	);

   /**
	* Stores display names for the meridiems in the available locales.
	* 
	* @access	private
	* @var		array
	*/
	var $displayNames = array(
		'C' => array(
			'am' => 'am',
			'pm' => 'pm',
		),
		'de' => array(
			'am' => 'Vormittag',
			'pm' => 'Nachmittag',
		),
		'fr' => array(
			'am' => 'Matin',
			'pm' => 'Après-midi',
		)
	);
	
   /**
	* Retrieves values for the selector when using the preset mode.
	* 
	* @access	private
	* @return	array	$values	Values list in patForms format
	*/
	function getValues()
	{
		$values = array();

		foreach( $this->displayNames[$this->locale] as $meridiem => $label ) {
			
			if( $this->token == 'A' ) {
				$label = strtoupper( $label );
			}
			
			array_push(
				$values,
				array(
					'label' => $label,
					'value' => $meridiem
				)
			);
		}
		
		return $values;
	}
	
   /**
	* Custom serialize method that checks fot the presence of the related
	* 12-hour format token. If not present, this will trigger a notice and
	* cancel the serialization process.
	* 
	* @access	public
	* @return	string	$content	The serialized element
	*/
	function serialize()
	{
		$validTokens = array(
			'g',
			'h'
		);
		
		foreach( $validTokens as $token ) {
			if( $this->parent->tokenUsed( $token ) ) {
				return parent::serialize();
			}
		}
		
		patErrorManager::raiseNotice(
			PATFORMS_ELEMENT_DATE_ELEMENT_MERIDIEM_NOTICE_NOT_VALID_HERE,
			'You have used a meridiem in your date format, but a meridiem only makes sense if you also set the hour format to 12-hour format. Meridiem ignored.'
		);

		return '';
	}
}

?>