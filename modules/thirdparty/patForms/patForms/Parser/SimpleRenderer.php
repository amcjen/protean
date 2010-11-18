<?PHP
/**
 * Renderer based on patForms_Parser
 *
 * This class can be used as a parser that is also
 * a renderer. It makes it quite easy to create working
 * forms from a form template
 *
 * $Id: SimpleRenderer.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @author		Stephan Schmidt <s.schmidt@metrix.de>
 * @package		patForms
 * @subpackage	Parser
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */
 
/**
 * Renderer based on patForms_Parser
 *
 * This class can be used as a parser that is also
 * a renderer. It makes it quite easy to create working
 * forms from a form template
 *
 * @author		Stephan Schmidt <s.schmidt@metrix.de>
 * @package		patForms
 * @subpackage	Parser
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */
class patForms_Parser_SimpleRenderer extends patForms_Parser
{
   /**
	* gathers serialized data from all elements and replaces them in the outputFile.
	*
	* @access	public
	* @param	object	&$patForms			Reference to the patForms object
	* @param	mixed	$args				optional arguments
	* @return	string	$html				HTML code
	*/
	function render(&$patForms, $args = null)
	{
	    $renderer = &patForms::createRenderer('String');
	    $renderer->setTemplate($this->getHTML());
	    $renderer->setPlaceholder($this->_placeholder, 'id');
	    $renderer->setFormPlaceholders($this->_placeholder_form_start, $this->_placeholder_form_end);
	    
	    return $renderer->render($patForms, $args);
	}
}
?>