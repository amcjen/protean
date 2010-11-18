<?php
/**
 * patForms multiplier filter
 *
 * Will multiply values returned by patForms
 * and divide them while setting values
 *
 * $Id: Multiplier.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Filter
 */

/**
 * patForms multiplier filter
 *
 * Will multiply values returned by patForms
 * and divide them while setting values
 *
 * @package		patForms
 * @subpackage	Filter
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 * @version		1.0
 */
class patForms_Filter_Multiplier extends patForms_Filter
{
   /**
	* type of the filter
	*
	* @access	private
	*/
	var $_type	=	PATFORMS_FILTER_TYPE_PHP;

   /**
	* multiplier
	*
	* @access	private
	* @var		integer
	*/
	var $_multiplier	=	1;

   /**
	* change the multiplier value
	*
	* @access	public
	* @param	integer		new multiplier value
	*/
	function setMultiplier( $multi )
	{
		$this->_multiplier	=	$multi;
	}
	
   /**
	* Filter value that is returned by patForms
	*
	* This method is applied when patForms_Element::getValue()
	* or patForms::getValues() is called.
	*
	* @abstract
	* @access	public
	* @param	string	value
	* @return	float	filtered value
	*/
	function out( $value )
	{
		$value	=	(float)$value * $this->_multiplier;
		return $value;
	}

   /**
	* Filter value that is passed to patForms
	*
	* This method is applied when patForms_Element::setValue()
	* or patForms::setValues() is called.
	*
	* @abstract
	* @access	public
	* @param	mixed	value
	* @return	mixed	filtered value
	*/
	function in( $value )
	{
		$value	=	(string)( $value / $this->_multiplier );
		return $value;
	}
}
?>