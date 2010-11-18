<?php
/**
 * patForms array renderer class.
 *
 * $Id: Array.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Renderer
 */

/**
 * patForms array renderer -  gathers serialized data from all elements,
 * and returns it along with all attributes in a handy array that can directly
 * be added to a template to display the form.
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Renderer
 * @author		Sebastian Mordziol <argh@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 * @todo		add javascript support
 */
class patForms_Renderer_Array extends patForms_Renderer
{
   /**
	* gathers serialized data from all elements, and returns it along with all
	* attributes in a handy array that can directly be added to a template to
	* display the form.
	*
	* @access	public
	* @param	object	&$patForms			Reference to the patForms object
	* @return	string	$serializedElements	The list with elements.
	*/
	function render( &$patForms )
	{
		$serializedElements = array();
		$elements =& $patForms->getElements();

		$cnt = count( $elements );
		for( $i=0; $i < $cnt; $i++ ) {
			// first, serialize the element as this also initializes the attribute collection.
			// if an error occurrs here, we just ignore it - we don't want the whole serialization
			// process to fail because of a warning, for ex.
			$serialized	=	$elements[$i]->serialize();
			if( patErrorManager::isError( $serialized ) ) {
				continue;
			}

			// now get the attributes
			$meta = $elements[$i]->getAttributes();
			$meta['element'] = $serialized;
			$meta['elementName'] = $elements[$i]->getElementName();

			// skip the datasource instance if present
			if (isset($meta['datasource'])) {
				unset($meta['datasource']);
			}

			array_push( $serializedElements, $meta );
		}

		return $serializedElements;
	}
}
?>