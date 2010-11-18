<?php
/**
 * patForms Date subelement Minute
 *
 * Handles minutes input and validation for the date element
 * 
 * $Id: Minute.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 */

/**
 * patForms Date subelement Minute
 *
 * Handles minutes input and validation for the date element
 * 
 * $Id: Minute.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 */
class patForms_Element_Date_Element_Minute extends patForms_Element_Date_Element
{
   /**
	* Stores all needed token configurations, i.e. the length and output
	* format of the corresponding values.
	* 
	* @access	private
	* @var		array
	*/
	var $tokens = array(
		'i' => array(
			'length'	=>	2,
			'format'	=>	'%02d',
			'getter'	=>	'getMinute',
			'setter'	=>	'setMinute',
		),
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
		
		for( $i=0; $i <= 59; $i++ )	{
			$label = sprintf( $this->tokens[$this->token]['format'], $i );
			
			array_push( 
				$values, 
				array(
					'label' => $label,
					'value'	=> $label
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
		
		if( $value < 0 || $value > 59 ) {
			return false;
		}
		
		return true;
	}
}

?>