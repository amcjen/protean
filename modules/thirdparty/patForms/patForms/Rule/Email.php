<?php
/**
 * patForms Rule Email
 *
 * $Id: Email.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Rules
 */

/**
 * patForms Rule Email
 *
 * Rule to check an eMail address. This can be done in three checks:
 * - syntax
 * - MX
 * - user
 *
 * The MX and user check require PEAR Net_DNS. To check the
 * user you will also need to install Net_SMTP.
 *
 * @package		patForms
 * @subpackage	Rules
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Rule_Email extends patForms_Rule
{
   /**
	* name of the rule
	*
	* @abstract
	* @access	private
	*/
	var	$ruleName = 'Email';

   /**
	* define error codes and messages for the rule
	*
	* @access	private
	* @var		array	$validatorErrorCodes
    * @todo     translate error messages
	*/
	var	$validatorErrorCodes  =   array(
        "C"   =>   array(
             1   =>   "E-Mail address can only be 132 characters long.",
             2   =>   "The E-Mail address may contain no blank spaces.",
             3   =>   "The E-Mail address must contain only English caracters.",
             4   =>   'The E-Mail address must contain exactly one @ symbol.',
             5   =>   "The E-Mail address contains invalid characters.",
             6   =>   "The E-Mail address cannot contain two successive periods.",
             7   =>   "Please enter a valid E-mail address format [name@domainname.com].",
             8   =>   "E-Mail domain could not be found.",
             9   =>   "The domain specified does not recognise the given email address."
        ),
		"de" =>	array(
			1	=>	"Eine E-Mail Adresse darf maximal 132 Zeichen lang sein.",
			2	=>	"Die E-Mail Adresse darf keine Leerzeichen enthalten.",
			3	=>	"Die E-Mail Adresse darf keine Umlaute oder '§' enthalten.",
			4	=>	'Eine E-Mail-Adresse muss genau ein \'@\' enthalten.',
			5	=>	"Die E-Mail Adresse enthŠlt ungŸltige Zeichen.",
			6	=>	"Die E-Mail Adresse darf keine zwei aufeinanderfolgenden Punkte enthalten.",
			7	=>	"Das Eingabefeld enthŠlt kein gŸltiges Format. Bitte Šndern Sie Ihre Eingabe entsprechend dem Muster [name@domainname.de].",
			8	=>	"Die Domain der E-Mail Adresse konnte nicht gefunden werden.",
			9	=>	"Der Mailserver verweigert die Annahme von E-Mails fŸr die E-Mail-Adresse."
		),
		"fr"=>	array(
			1	=>	"Une adresse Email ne peut contenir que 132 caractres au maximum.",
			2	=>	"Une adresse Email ne doit pas contenir d'espaces.",
			3	=>	"Une adresse Email ne doit pas contenir d'accents ou d'autres caractres spŽciaux comme le .",
			4	=>	'Une adresse Email doit contenir exactement un \'@\'.',
			5	=>	"L'adresse Email contient des caractres invalides.",
			6	=>	"Une adresse Email ne doit pas contenir deux ou plus de points (.) consŽcutifs.",
			7	=>	"Format invalide. Veuillez entrer une adresse Email dans un format valide ([nom@h™te.fr] par exemple).",
			8	=>	"Le serveur h™te de l'adresse Email n'a pas pu tre contactŽ.",
			9	=>	"Le serveur h™te de l'adresse Email refuse les messages pour cette adresse."
		),
	);

   /**
	* flag to indicate whether the MX server should be checked
	* 
	* @var		boolean
	* @access	private
	*/
	var $_checkMx = false;

   /**
	* flag to indicate whether the username should be checked
	* 
	* @var		boolean
	* @access	private
	*/
	var $_checkUser = false;

   /**
	* enable the MX check
	*
	* @access	public
	* @param	boolean
	*/
	function enableMxCheck($flag = true)
	{
		$this->_checkMx = $flag;
	}
	
   /**
	* enable the User check
	*
	* @access	public
	* @param	boolean
	*/
	function enableUserCheck($flag = true)
	{
		$this->_checkUser = $flag;
	}
	
   /**
	* method called by patForms or any patForms_Element to validate the
	* element or the form.
	*
	* @access	public
	* @param	object patForms	form object
	*/
	function applyRule( &$element, $type = PATFORMS_RULE_BEFORE_VALIDATION )
	{
		$value = $element->getValue();
		if (empty($value)) {
			return true;
		}
		
		if( strlen( $value ) > 132 ) {
			$this->addValidationError(1);
			return false;
		}
		
		//	check for spaces
		if( preg_match( "/\s/", $value ) ) {
			$this->addValidationError(2);
		}

		//	check for more than one '@'
		if( substr_count( $value, '@' ) != 1 ) {
			$this->addValidationError(4);
			return false;
		}

		//	check for valid chars in email
		$validChars	=	preg_quote( "abcdefghijklmnopqrstuvwxyz1234567890@.+_-" );
		if( !preg_match( "/^[".$validChars."]+$/i", $value ) ) {
			$this->addValidationError(5);
			return false;
		}
		
		if (strstr( $value, '..' )) {
			$this->addValidationError(6);
			return false;
		}

		//	check format
		if( !preg_match( "/^.*[^.]@[^-.].*\..{2,}$/", $value ) ) {
			$this->addValidationError(7);
			return false;
		}

		//	check for existing mailserver
		if ($this->_checkMx || $this->_checkUser) {
			require_once 'PEAR.php';
			require_once 'Net/DNS.php';
			require_once 'Net/DNS/Resolver.php';
			
			$resolver = new	Net_DNS_Resolver();
			$domain   = substr( strchr( $value, '@' ), 1 );
			$mxResult = $resolver->send( $domain, "MX", "IN" );
			
			if( PEAR::isError( $mxResult ) || empty( $mxResult->answer ) ) {
				$this->addValidationError(8);
				return false;
			}

			//	ask mailserver, whether user exists
			if ($this->_checkUser) {
				require_once 'Net/SMTP.php';
				$found     = false;
				$mxServers = $mxResult->answer;
				$cnt       = count( $mxServers );
				for ($i = 0; $i < $cnt; $i++) {
					
					if (isset($mxServers[$i]->exchange)) {
						$smtp	=	new Net_SMTP( $mxServers[$i]->exchange );
						$smtp->connect();
						$result	=	$smtp->mailFrom( 'test@w3c.org' );
						if (PEAR::isError($result)) {
							continue;
						}
						$result	= $smtp->rcptTo( $value );
						if (PEAR::isError( $result )) {
							continue;
						}
						$found	=	true;
						break;
					}
				}

				if ($found === false) {
					$this->addValidationError(9);
					return false;
				}
			}
		}
		return true;
	}
}
?>
