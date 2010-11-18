<?php
/**
 * patForms Date subelement Year
 *
 * Handles year input and validation for the date element
 * 
 * $Id: Year.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 */

/**
 * patForms Date subelement Year
 *
 * Handles year input and validation for the date element
 * 
 * $Id: Year.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 */
class patForms_Element_Date_Element_Year extends patForms_Element_Date_Element
{
   /**
	* Stores all needed token configurations, in this case only the 
	* lengths of the output strings.
	* 
	* @access	private
	* @var		array
	*/
	var $tokens = array(
		'Y' => array(
			'length'	=>	4,
			'format'	=>	'%04d',
			'getter'	=>	'getYear',
			'setter'	=>	'setYear',
		),
		'y' => array(
			'length'	=>	2,
			'format'	=>	'%02d',
			'getter'	=>	'getYear',
			'setter'	=>	'setYear',
		),
	);
	
   /**
	* Intializes needed attributes.
	* 
	* @access	private
	*/
	function initAttributes()
	{
		if( $this->mode != 'presets' ) {
			$this->attributes['size'] = $this->tokens[$this->token]['length'];
			$this->attributes['maxlength'] = $this->tokens[$this->token]['length'];
		}
	}
	
   /**
	* Retrieves values for the selector when using the preset mode.
	* 
	* @access	private
	* @return	array	$values	Values list in patForms format
	*/
	function getValues()
	{
		$start = $this->minDate->getYear();
		$end = $this->maxDate->getYear();
		
		$values = array();
		for( $i = $start; $i <= $end; $i++ ) {
			$value = sprintf( $this->tokens[$this->token]['format'], $i );

			array_push( 
				$values, 
				array(
					'label' => $value,
					'value'	=> $value
				)
			);
		}
		
		return $values;
	}
}

?>