<?php
/**
 * patForms observer base class
 *
 * $Id: Observer.php,v 1.1 2006/04/03 20:41:04 eric Exp $
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
class patForms_Observer
{
   /**
	* create a new observer
	*
	* @access	public
	* @param	string	id
	*/
	function patForms_Observer()
	{
	}
	
   /**
	* method called by patForms or any patForms_Element to signalise
	* an event
	*
	* @abstract
	* @access	public
	* @param	object	    Either a patForms or patForms_Element object
	* @param	string	    Property, that has changed (currently only 'status' is possible)
	* @param	mixed	    new value of the property
	* @return	boolean     should always return true
	*/
	function notify( &$container, $property, $value )
	{
		// your code
	}
}
?>