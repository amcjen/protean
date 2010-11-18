<?PHP
/**
 * Renderer based on patForms_Parser and patTemplate
 *
 * $Id: patTemplateRenderer.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @author		Stephan Schmidt <s.schmidt@metrix.de>
 * @package		patForms
 * @subpackage	Parser
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */
 
/**
 * Renderer based on patForms_Parser and patTemplate
 *
 * Use this parser, if you want to use the forms together
 * with patTemplate
 *
 * Possible arguements to renderForm():
 * - template : name of the template (_not_ filename) in which the elements will be added
 * - errorTemplate : name of the template to which the error messages will be added (will be repeated, if more than one error occured)
 * - errorTemplateContainer : name of the template which contains the errorTemplate. If errors occured, its visibility will be set to visible
 *
 * @author		Stephan Schmidt <s.schmidt@metrix.de>
 * @package		patForms
 * @subpackage	Parser
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 * @version		1.0
 */
class patForms_Parser_patTemplateRenderer extends patForms_Parser
{
   /**
    * patTemplate object
	* @access	private
	*/
	var $_tmpl	=	null;
	
   /**
    * checks whether errors have been rendered or not
	* @access	private
	*/
	var $errorsRendered = array();
	
   /**
    * set the reference to the patTemplate object
	*
	* @access	public
	* @param	object	patTemplate object
	*/
	function setTemplate(&$tmpl)
	{
		$this->_tmpl = &$tmpl;
	}

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
		if ($this->_tmpl == null) {
			$this->_tmpl = &patForms_Parser::getStaticProperty('tmpl');
		}

	    $renderer = &patForms::createRenderer('patTemplate');
	    $renderer = &new patForms_Renderer_patTemplate();
	    $renderer->setTemplate($this->_tmpl);
	    
	    $renderer->setPlaceholder($this->_placeholder);
	    $renderer->setFormPlaceholders($this->_placeholder_form_start, $this->_placeholder_form_end);
	    
		// check, whether the file has been loaded
		if (!$this->_tmpl->exists($args['template'])) {
			$this->_tmpl->readTemplatesFromFile($this->_outputFile);
		}

	    return $renderer->render($patForms, $args);
	}

   /**
	* get the placeholder for an element
	*
	* @access	protected
	* @param	string		element name
	* @param	string		name of the placeholder template
	* @return	string		placeholder
	*/
	function _getPlaceholderForElement( $element, $template = 'placeholder' )
	{
		// adjust the case
		switch( $this->_placeholder_case )
		{
			case 'upper':
				$element	=	strtoupper( $element );
				break;
			case 'lower':
				$element	=	strtolower( $element );
				break;
			default:
				break;
		}
		
		return	sprintf( '{'.$this->{'_'.$template}.'}', $element );
	}

   /**
	* get the placeholder for a form tag
	*
	* @access	protected
	* @param	string		name of the form
	* @param	string		type (start|end)
	* @return	string		placeholder
	*/
	function _getPlaceholderForForm( $form, $type )
	{
		// adjust the case
		switch( $this->_placeholder_case )
		{
			case 'upper':
				$form	=	strtoupper( $form );
				break;
			case 'lower':
				$form	=	strtolower( $form );
				break;
			default:
				break;
		}

		$template	=	'_placeholder_form_'.$type;
		return	sprintf( '{'.$this->$template.'}', $form );
	}
}
?>