<?php
/**
 * patForms Date subelement Month
 *
 * Handles month input and validation for the date element
 * 
 * $Id: Month.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 */

/**
 * patForms Date subelement Month
 *
 * Handles month input and validation for the date element
 * 
 * $Id: Month.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		public
 * @package		patForms
 * @subpackage	Element
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 */
class patForms_Element_Date_Element_Month extends patForms_Element_Date_Element
{
   /**
	* Stores all needed token configurations, i.e. the length and output
	* format of the corresponding values.
	* 
	* @access	private
	* @var		array
	*/
	var $tokens = array(
		'F' => array(	// full textual representation
			'length'	=>	2,
			'format'	=>	'%s',
			'getter'	=>	'getMonth',
			'setter'	=>	'setMonth',
		),
		'm' => array(	// numeric, with leading zeros
			'length'	=>	2,
			'format'	=>	'%02d',
			'getter'	=>	'getMonth',
			'setter'	=>	'setMonth',
		),
		'M' => array(	// string, short three-letter version
			'length'	=>	2,
			'format'	=>	'%s',
			'getter'	=>	'getMonth',
			'setter'	=>	'setMonth',
		),
		'n' => array(	// numeric, without leading zeros
			'length'	=>	2,
			'format'	=>	'%01d',
			'getter'	=>	'getMonth',
			'setter'	=>	'setMonth',
		),
	);
	
   /**
	* Stores a compatibility table of date tokens that will be converted if used
	* to the alternate token specified.
	* 
	* @access	private
	* @var		array
	*/
	var $compatTable = array(
		'F'	=>	'm',
		'M'	=>	'm',
	);
	
   /**
	* Stores month names in the available locales.
	* 
	* @access	private
	* @var		array
	*/
	var $monthNames = array(
		'C'	=>	array(
			'1'		=>	'January',
			'2'		=>	'February',
			'3'		=>	'March',
			'4'		=>	'April',
			'5'		=>	'May',
			'6'		=>	'June',
			'7'		=>	'July',
			'8'		=>	'August',
			'9'		=>	'September',
			'10'	=>	'October',
			'11'	=>	'November',
			'12'	=>	'December',
		),
		'de' => array(
			'1'		=>	'Januar',
			'2'		=>	'Februar',
			'3'		=>	'März',
			'4'		=>	'April',
			'5'		=>	'Mai',
			'6'		=>	'Juni',
			'7'		=>	'Juli',
			'8'		=>	'August',
			'9'		=>	'September',
			'10'	=>	'Oktober',
			'11'	=>	'November',
			'12'	=>	'Dezember',
		),
		'fr' => array(
			'1'		=>	'Janvier',
			'2'		=>	'Février',
			'3'		=>	'Mars',
			'4'		=>	'Avril',
			'5'		=>	'Mai',
			'6'		=>	'Juin',
			'7'		=>	'Juillet',
			'8'		=>	'Août',
			'9'		=>	'Septembre',
			'10'	=>	'Octobre',
			'11'	=>	'Novembre',
			'12'	=>	'Décembre',
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
		$start = date( 'n', time() );
		
		$values = array();
		for( $i = 1; $i <= 12; $i++ ) {
			$value = sprintf( $this->tokens[$this->getCompatToken()]['format'], $i );
			
			switch( $this->token ) {
				case 'F':
					$label = $this->monthNames[$this->locale][$i];
					break;
				case 'M':
					$label = substr( $this->monthNames[$this->locale][$i], 0, 3 );
					$fieldSize = 3;
					break;
				case 'm':
					$label = sprintf( $this->tokens[$this->token]['format'], $i );
					break;
				case 'n':
					$label = $i;
					break;
			}
			
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
		
		if( $value < 1 || $value > 12 ) {
			return false;
		}
		
		return true;
	}
}

?>