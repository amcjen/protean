<?php
/**
 * patForms filter that uses any PHP functions to filter values
 *
 * Removes leading and trailing whitespace from
 * user input
 *
 * $Id: Function.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Filter
 */

/**
 * patForms filter that uses any PHP functions to filter values
 *
 * @package		patForms
 * @subpackage	Filter
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 * @version		1.0
 */
class patForms_Filter_Function extends patForms_Filter
{
   /**
	* in function
	*
	* @access	private
	* @var		mixed
	*/
	var $_inFunction = null;

   /**
	* out function
	*
	* @access	private
	* @var		mixed
	*/
	var $_outFunction = null;

   /**
	* type of the filter
	*
	* @access	private
	*/
	var $_type = PATFORMS_FILTER_TYPE_HTTP;

   /**
	* set the function for incoming data
	*
	* @access	public
	* @param	mixed		function name | array with class/object and function name
	* @return	boolean
	*/
	function setInFunction($func)
	{
		$this->_inFunction = $func;
		return true;
	}
	
   /**
	* set the function for outgoing data
	*
	* @access	public
	* @param	mixed		function name | array with class/object and function name
	* @return	boolean
	*/
	function setOutFunction($func)
	{
		$this->_outFunction = $func;
		return true;
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
		if (is_null($this->_outFunction)) {
			return $value;
		}
		if (!is_callable($this->_outFunction)) {
			return $value;
		}
		$value = call_user_func($this->_outFunction, $value);
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
		if (is_null($this->_inFunction)) {
			return $value;
		}
		if (!is_callable($this->_inFunction)) {
			return $value;
		}
		$value = call_user_func($this->_inFunction, $value);
		return $value;
	}
}
?>