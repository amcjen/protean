<?php
/**
 * patForms Date subelement Hour
 *
 * Handles hour input and validation for the date element
 * 
 * $Id: Hour.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 */

/**
 * patForms Date subelement Hour
 *
 * Handles hour input and validation for the date element
 * 
 * $Id: Hour.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 */
class patForms_Element_Date_Element_Hour extends patForms_Element_Date_Element
{
   /**
	* Stores all needed token configurations, i.e. the length and output
	* format of the corresponding values.
	* 
	* @access	private
	* @var		array
	*/
	var $tokens = array(
		'g' => array(	// 12 hour format without leading zeros
			'length'	=>	2,
			'format'	=>	'%01d',
			'getter'	=>	'getHour',
			'setter'	=>	'setHour',
		),
		'G' => array(	// 24 hour format without leading zeros
			'length'	=>	2,
			'format'	=>	'%01d',
			'getter'	=>	'getHour',
			'setter'	=>	'setHour',
		),
		'h' => array(	// 12 hour format with leading zeros
			'length'	=>	2,
			'format'	=>	'%02d',
			'getter'	=>	'getHour',
			'setter'	=>	'setHour',
		),
		'H' => array(	// 24 hours format with leading zeros
			'length'	=>	2,
			'format'	=>	'%02d',
			'getter'	=>	'getHour',
			'setter'	=>	'setHour',
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
		switch( $this->token ) {
			case 'g':
			case 'h':
				$start = 1;
				$end = 12;
				break;

			case 'G':
			case 'H':
				$start = 0;
				$end = 23;
				break;
		}
		
		$values = array();
		
		for( $i=$start; $i <= $end; $i++ )	{
			$label = sprintf( $this->tokens[$this->token]['format'], $i );
			$value = sprintf( $this->tokens[$this->getCompatToken()]['format'], $i );
			
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
		
		if( $value < 0 || $value > 23 ) {
			return false;
		}
		
		return true;
	}
}

?>