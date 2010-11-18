<?php
/**
 * patForms observer that will set the mode of the subject
 * to 'readonly' once the supplied data is OK.
 *
 * $Id: ReadonlyFinished.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Observers
 */

/**
 * patForms observer that will set the mode of the subject
 * to 'readonly' once the supplied data is OK.
 *
 * @package		patForms
 * @subpackage	Observers
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Observer_ReadonlyFinished extends patForms_Observer
{
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

		if( $value != 'validated' )
			return true;
			
		$container->setMode( 'readonly' );
		return true;
	}
}
?>