<?php
/**
 * patForms "storage" class for mails.
 *
 * This is no real storage class, but it follows the same
 * API and can be used like a storage.
 *
 * It only sends an eMail containing all form data.
 *
 * $Id: Mail.php,v 1.1 2006/04/03 20:41:08 eric Exp $
 *
 * @package		patForms
 * @subpackage	Storage
 */

/**
 * Uses PEAR::Mail
 */
require_once 'Mail.php';
 
/**
 * patForms "storage" class for mails.
 *
 * This is no real storage class, but it follows the same
 * API and can be used like a storage.
 *
 * It only sends an eMail containing all form data.
 *
 * @package		patForms
 * @subpackage	Storage
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Storage_Mail extends patForms_Storage
{
   /**
    * Mail driver to use
    *
    * @access   private
    * @var      string
    */
    var $_mailDriver = 'mail';

   /**
    * Parameters for the mail driver
    *
    * @access   private
    * @var      string
    */
    var $_mailDriverParams = array();

   /**
    * Recipient(s) of the mail
    *
    * @access   private
    * @var      array
    */
    var $_recipient = array();

   /**
    * Headers of the mail
    *
    * @access   private
    * @var      array
    */
    var $_headers = array(
                            'Subject'     => 'patForms auto-generated mail',
                            'X-Mailed-By' => 'patForms_Storage_Mail $Revision: 1.1 $',
                            'From'        => 'patForms_Storage_Mail'
                        );

   /**
    * prepend text to the mail body
    *
    * @access   private
    * @var      string
    */
    var $_prependText = null;

   /**
    * append text to the mail body
    *
    * @access   private
    * @var      string
    */
    var $_appendText = null;
    
   /**
    * Set the recipient of the mail
    *
    * @access   public
    * @param    string|array
    */
    function setRecipient($recipient)
    {
        if (!is_array($recipient)) {
        	$recipient = array($recipient);
        }
    	$this->_recipient = $recipient;
    }

   /**
    * Set the headers of the mail
    *
    * @access   public
    * @param    array
    */
    function setHeaders($headers)
    {
    	$this->_headers = array_merge($this->_headers, $headers);
    }

   /**
    * prepend text to the mail content
    *
    * @access   public
    * @param    string
    */
    function prependText($text)
    {
        $this->_prependText = $text;
    }

   /**
    * append text to the mail content
    *
    * @access   public
    * @param    string
    */
    function appendText($text)
    {
        $this->_appendText = $text;
    }

   /**
    * Set the mail driver to use
    *
    * @access   public
    * @param    string      mail driver name
    * @param    array       mail driver parameters
    * @link     http://pear.php.net/manual/en/package.mail.mail.factory.php
    */
    function setMailDriver($driver, $params = array())
    {
        $this->_mailDriver = $driver;
        $this->_mailDriverParams = $params;
    }

   /**
	* Mail the form data
	*
	* @access	public
	* @param	object patForms		patForms object that should be stored
	* @return	boolean				true on success
	*/
	function storeEntry(&$form)
	{
		$elements = $form->getElements();

		// get all attributes
		$fields = array();
		foreach ($elements as $element) {
            $value = $element->convertValueToHumanReadable($element->getValue());
            $fields[$element->getAttribute('label')] = $value;
		}
		
		$maxLength = max(array_map('strlen', array_keys($fields)));
		
		// prepend text
		if (!empty($this->_prependText)) {
			$mailBody = $this->insertValuesIntoText($this->_prependText, $form);
		} else {
    		$mailBody = '';
		}
		
		// insert form values into mailbody
		foreach ($fields as $label => $value) {
			$mailBody .= sprintf("%s : %s\n", str_pad($label, $maxLength), $value);
		}

		if (!empty($this->_appendText)) {
			$mailBody .= $this->insertValuesIntoText($this->_appendText, $form);
		}

		$headers = array();
		foreach ($this->_headers as $key => $value) {
			$headers[$key] = $this->insertValuesIntoText($value, $form);
		}
		$mail = &Mail::factory($this->_mailDriver, $this->_mailDriverParams);
        if (PEAR::isError($mail)) {
            return patErrorManager::raiseError('patForms::Storage::Mail::001', $mail->getMessage());
        }
		$result = $mail->send($this->_recipient, $headers, $mailBody);
        if (PEAR::isError($result)) {
            return patErrorManager::raiseError('patForms::Storage::Mail::001', $result->getMessage());
        }
		return true;
	}

   /**
    * insert the form values into any text
    *
    * @access   private
    * @param    string
    * @param    patForms
    */
	function insertValuesIntoText($text, $form)
	{
		$values = array_merge($this->_staticValues, $form->getValues());
		foreach ($values as $name => $value) {
   			$text = str_replace('{'.strtoupper($name).'}', $value, $text);
		}
		return $text;
	}
	
   /**
    * validate the form
    *
    * @access   public
	* @param	object  $form  The form
	* @return   True or false, the validation result
    */
	function validateEntry(&$form)
	{
	    return true;
	}

   /**
	* get an entry
	*
	* This tries to find an entry in the storage container
	* that matches the current data that has been set in the
	* form and populates the form with the data of this
	* entry
	*
	* @access	public
	* @param	object patForms		patForms object that should be stored
	* @return	boolean				true on success
	*/
	function loadEntry( &$form )
	{
	    return true;
	}
}
?>