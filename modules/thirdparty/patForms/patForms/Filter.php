<?php
/**
 * patForms filter base class - extend this to create your own filters.
 *
 * $Id: Filter.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Filter
 */

/**
 * patForms filter base class - extend this to create your own filters.
 *
 * @package		patForms
 * @subpackage	Filter
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Filter
{
   /**
	* type of the filter
	*
	* Possible values are:
	* - PATFORMS_FILTER_TYPE_HTTP
	* - PATFORMS_FILTER_TYPE_PHP
	*
	* @access	private
	*/
	var $_type	=	PATFORMS_FILTER_TYPE_PHP;

   /**
	* get the type of the filter as defined
	* in the_type property
	*
	* @access	public
	* @return	integer	filter type
	*/
	function getType()
	{
		return $this->_type;
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
	* @return	mixed	filtered value
	*/
	function out( $value )
	{
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
		return $value;
	}
}
?>