<?php
/**
 * patForms trim filter
 *
 * Removes leading and trailing whitespace from
 * user input
 *
 * $Id: Trim.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Filter
 */

/**
 * patForms trim filter
 *
 * Removes leading and trailing whitespace from
 * user input
 *
 * @package		patForms
 * @subpackage	Filter
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 * @version		1.0
 */
class patForms_Filter_Trim extends patForms_Filter
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
        if (is_string($value)) {
            $value = trim( $value );
        }
		return $value;
	}
}
?>