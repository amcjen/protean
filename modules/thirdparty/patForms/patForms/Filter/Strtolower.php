<?php
/**
 * patForms strtolower filter
 *
 * Converts value to lowercase
 *
 * $Id: Strtolower.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Filter
 */

/**
 * patForms strtolower filter
 *
 * Converts value to lowercase
 *
 * $Id: Strtolower.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Filter
 * @author		Sebastian 'The Argh' Mordziol <argh@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 * @version		$Revision: 1.1 $
 */
class patForms_Filter_Strtolower extends patForms_Filter
{
   /**
	* type of the filter
	*
	* @access	private
	*/
	var $_type	=	PATFORMS_FILTER_TYPE_HTTP;

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
		return $value;
	}

   /**
	* Filter value that is passed to patForms
	*
	* @abstract
	* @access	public
	* @param	mixed	value
	* @return	mixed	filtered value
	*/
	function in( $value )
	{
		$value	=	strtolower( $value );
		return $value;
	}
}
?>