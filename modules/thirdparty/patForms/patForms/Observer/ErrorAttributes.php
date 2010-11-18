<?php
/**
 * patForms observer base class
 *
 * $Id: ErrorAttributes.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Observers
 */

/**
 * patForms observer base class
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Observers
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Observer_ErrorAttributes extends patForms_Observer
{
   /**
	* attributes that will be set if the status is 'error'
	*
	* @access	private
	* @param	array
	*/
	var $_errorAttributes = array();

   /**
	* set the attributes that should be used on error
	*
	* @access	public
	* @param	array		new attributes
	*/
	function setAttributes( $atts )
	{
		$this->_errorAttributes = $atts;
	}

   /**
	* method called by patForms or any patForms_Element to signalise
	* an event
	*
	* @access	public
	* @param	object	    Either a patForms or patForms_Element object
	* @param	string	    Property, that has changed (currently only 'status' is possible)
	* @param	mixed	    new value of the property
	* @return	boolean     should always return true
	*/
	function notify( &$container, $property, $value )
	{
		/**
		 * act only on a status change
		 */
		if( $property != 'status' )
			return true;

		if( $value != 'error' )
			return true;
			
		$container->setAttributes( $this->_errorAttributes );
		return true;
	}
}
?>