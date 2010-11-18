<?php
/**
 * patForms XSS filter
 *
 * Removes javascript and vbscript from user input.
 *
 * $Id: XSS.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Filter
 */

/**
 * patForms XSS filter
 *
 * Removes javascript and vbscript from user input.
 *
 * @package		patForms
 * @subpackage	Filter
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 * @version		1.0
 */
class patForms_Filter_XSS extends patForms_Filter
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
		$value	=	preg_replace( '!<(vb)?script[^>]*>.*</(vb)?script.*>!ims', '', $value );
		return $value;
	}
}
?>