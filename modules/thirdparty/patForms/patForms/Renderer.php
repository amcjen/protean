<?php
/**
 * patForms renderer base class - extend this to create your own renderers.
 *
 * $Id: Renderer.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Renderer
 */

/**
 * patForms renderer base class - extend this to create your own renderers.
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Renderer
 * @author		Sebastian Mordziol <argh@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Renderer
{
   /**
	* method called by patForms to retrieve the rendered form content.
	*
	* @access	public
	* @param	object	&$patForms	Reference to the patForms object
	* @param    array       Arguments for the renderer
	*/
	function render(&$patForms, $args = array())
	{
		// your code
	}
}
?>