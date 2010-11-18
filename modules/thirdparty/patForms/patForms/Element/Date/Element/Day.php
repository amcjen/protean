<?php
/**
 * patForms Date subelement Day
 *
 * Handles day input and validation for the date element
 * 
 * $Id: Day.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 */

/**
 * patForms Date subelement Day
 *
 * Handles day input and validation for the date element
 * 
 * $Id: Day.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 */
class patForms_Element_Date_Element_Day extends patForms_Element_Date_Element
{
   /**
	* Stores all needed token configurations, i.e. the length and output
	* format of the corresponding values.
	* 
	* @access	private
	* @var		array
	*/
	var $tokens = array(
		'd' => array(
			'length'	=>	2,
			'format'	=>	'%02d',
			'getter'	=>	'getDay',
			'setter'	=>	'setDay',
		),
		'j' => array(
			'length'	=>	2,
			'format'	=>	'%01d',
			'getter'	=>	'getDay',
			'setter'	=>	'setDay',
		)
	);
	
   /**
	* Stores a compatibility table of date tokens that will be converted if used
	* to the alternate token specified.
	* 
	* @access	private
	* @var		array
	*/
	var $compatTable = array(
		'j'	=>	'd',
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
		
		for( $i=1; $i <= 31; $i++ )	{
			$label = sprintf( $this->tokens[$this->token]['format'], $i );
			$value = $i;
			
			array_push( 
				$values, 
				array(
					'label' => $label,
					'value'	=> $value
				)
			);
		}
		
		return $values;
	}
	
   /**
	* Validates the element.
	* 
	* @access	public
	* @return	bool	$valid	True if valid, false otherwise
	*/
	function validate()
	{
		$value = $this->getValue();
		if( is_null( $value ) ) {
			return false;
		}
		
		if( $value < 1 || $value > 32 ) {
			return false;
		}
		
		return true;
	}
}

?>