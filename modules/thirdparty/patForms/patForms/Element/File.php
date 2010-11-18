<?php
/**
 * File upload patForms element
 *
 * Upload-Element for single file upload. Handles upload and dumps
 * the file in custom directory.
 *
 * Special attributes for this element
 *  - uploaddir: destination for the uploaded file
 *  - overwrite: Overwrite exitsing file if set to "yes"
 *  - permissions: Set file-permissions for destination file
 *  - usesession: Stores uploaded file information in session.
 *    Though the user may not upload a file twice of validation of other form elements faile
 *  - tempdir: The place to store uploaded file until finalization
 *  - mimetype: Defines as many allowed filetype as you want. Supports asterisk for groups like: 'image/*'
 *  - replacechars: use Perl-Regular expression for filename-translation
 *
 * $Id: File.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 * @author		gERD Schaufelberger <gerd@php-tools.net>
 * @author		Sebastian Mordziol <argh@php-tools.net>
 * @todo		change html-output if file was already uploaded (use session)
 * @todo		make use of default attribute values
 * @todo		fix tempdir - attribute (default should work in Windows, too)
 */

/**
 * error definition: defined upload directory is not a directory.
 */
define( "PATFORMS_FILE_ERROR_NO_FILE_UPLOAD_DIR", 1301 );

/**
 * error definition: defined upload directory is not writeable.
 */
define( "PATFORMS_FILE_ERROR_UPLOAD_DIR_NOT_WRITEABLE", 1302 );

/**
 * error definition: moving upload file to destination failed.
 */
define( "PATFORMS_FILE_ERROR_CANNOT_MOVE_UPLOAD_FILE", 1303 );

/**
 * error definition: value of attribute "replace" has wrong format.
 */
define( "PATFORMS_FILE_ERROR_REPLACE_PREG_INVALID", 1304 );

/**
 * error definition: tmp-dir not writable or does not exist.
 */
define( "PATFORMS_FILE_ERROR_TEMPDIR_NOT_VALID", 1305 );

/**
 * error definition: wrong format of permission attribute
 */
define( "PATFORMS_FILE_ERROR_PERMISSION_ATTRIBUTE_NOT_VALID", 1306 );

/**
 * error definition, file upload requires php.ini 'file_uploads'
 */
define( "PATFORMS_FILE_ERROR_FILE_UPLOAD_OFF", 1307 );


/**
 * File upload field
 *
 * $Id: File.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @access		protected
 * @package		patForms
 * @subpackage	Element
 * @author		gERD Schaufelberger <gerd@php-tools.net>
 * @author		Sebastian Mordziol <argh@php-tools.net>
 * @license		LGPL, see license.txt for details
 */
class patForms_Element_File extends patForms_Element
{
   /**
	* Stores the name of the element - this is used mainly by the patForms
	* error management and should be set in every element class.
	* @access	public
	*/
	var $elementName	=	"File";

   /**
	* the type of the element - set this to the type of element you are creating
	* if you want to use the {@link patForms_Element::element2html()} method to
	* create the final HTML tag for your element.
	*
	* @access	public
	* @see		patForms_Element::element2html()
	*/
	var $elementType	=	array(	"html"	=>	"input" );

   /**
	* set here which attributes you want to include in the element if you want to use
	* the {@link patForms_Element::convertDefinition2Attributes()} method to automatically
	* convert the values from your element definition into element attributes.
	*
	* @access	protected
	* @see		patForms_Element::convertDefinition2Attribute()
	*/
	var	$attributeDefinition	=	array(

			"id"			=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
									),
			"name"			=>	array(	"required"		=>	true,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"type"			=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
									),
			"title"			=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"description"	=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array(),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"label"			=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array(),
									),
			"display"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"yes",
										"outputFormats"	=>	array(),
									),
			"edit"			=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"yes",
										"outputFormats"	=>	array(),
									),
			"required"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"yes",
										"outputFormats"	=>	array(),
									),
			"value"			=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
									),
			"style"			=>	array(	"required"		=>	false,
										"outputFormats"	=>	array( "html" ),
										"format"		=>	"string",
									),
			"class"			=>	array(	"required"		=>	false,
										"outputFormats"	=>	array( "html" ),
										"format"		=>	"string",
									),
			"onchange"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"onclick"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"onfocus"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"onmouseover"	=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"onmouseout"	=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"onblur"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
										"modifiers"		=>	array( "insertSpecials" => array() ),
									),
			"accesskey"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array( "html" ),
									),
			"position"		=>	array(	"required"		=>	false,
										"format"		=>	"int",
										"outputFormats"	=>	array(),
									),
			"tabindex"		=>	array(	"required"		=>	false,
										"format"		=>	"int",
										"outputFormats"	=>	array( "html" ),
									),
			"format"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array(),
									),
			"mimetype"		=>	array(	"required"		=>	false,
										"format"		=>	"array",
										"outputFormats"	=>	array(),
									),
			"uploaddir"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"outputFormats"	=>	array(),
									),
			"overwrite"		=>	array(	"required"		=>	false,
										"format"		=>	"boolean",
										"outputFormats"	=>	array(),
									),
			"tempdir"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	false,
										"outputFormats"	=>	array(),
									),
			"permissions"	=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"0666",
										"outputFormats"	=>	array(),
									),
			"replacename"	=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	'i/[^a-z0-9\.]/_',
										"outputFormats"	=>	array(),
									),
			"usesession"	=>	array(	"required"		=>	false,
										"format"		=>	"boolean",
										"outputFormats"	=>	array(),
									),
			"disabled"		=>	array(	"required"		=>	false,
										"format"		=>	"string",
										"default"		=>	"no",
										"outputFormats"	=>	array( "html" ),
									),
		);

    /**
     *	define error codes an messages for each form element
     *
     *  @access private
     *  @var	array	$validatorErrorCodes
     */
	var	$validatorErrorCodes  =   array(
		"C"	=>	array(
			1	=>	"Please enter the following information",
			2	=>	"Filename already exists - cannot overwrite file.",
			3	=>	"The uploaded file exceeds the maximum size of [MAXSIZE].",
			4	=>	"The uploaded file was only partially uploaded.",
			5	=>	"Wrong Filetype (mime: '[MIMETYPE]')",
			6	=>	"The uploaded file is empty."
		),
		"de" =>	array(
			1	=>	"Pflichtfeld. Bitte vervollstŠndigen Sie Ihre Angabe.",
			2	=>	"Dateiname existiert bereits - die Datei kann nicht Ÿberschrieben werden",
			3	=>	"Die Datei ist grš§er als die zugelassene Maximalgrš§e von [MAXSIZE].",
			4	=>	"Die Datei wurde nur teilweise hochgeladen",
			5	=>	"Falscher Dateityp (Mime-Typ: '[MIMETYPE]')",
			6	=>	"Die Datei ist leer."
		),
		"fr" =>	array(
			1	=>	"Ce champ est obligatoire.",
			2	=>	"Ce fichier existe dŽjˆ - il ne peut pas tre remplacŽ.",
			3	=>	"La taille du fichier est plus grande que la maximum autorisŽ de [MAXSIZE]",
			4	=>	"Le fichier n'a ŽtŽ tŽlŽchargŽ que partiellement",
			5	=>	"Type de fichier incorrect (Type: '[MIMETYPE]')",
			6	=>	"Le fichier est vide"
		)
	);

   /**
	* file permission - octal value
	*
	* This value has to be calculated from the attribute "permissions"
	*
    * @access private
	* @var int $permission
	*/
	var $permission = null;

   /**
	* temporary upload directory
	*
	* A place to store temporary files during session
	*
    * @access private
	* @var string $tempdir
	*/
	var $tempdir = false;

   /**
	* initialze file elemente
    * 
    * - check temporary file upload dir required when sessions are used
    * - check file upload in php.ini
    * 
	* @access	private
	* @return	mixed	$success	True on success, a patError object otherwise
	*/
    function _init()
    {
        if( !ini_get( 'file_uploads' ) ) {
            return patErrorManager::raiseError( PATFORMS_FILE_ERROR_FILE_UPLOAD_OFF,
                                                'File upload not possible',
                                                'php.ini doesn\'t allow file upload see: "file_uploads"' );
        }
        
        // require temporary directory if session-support is switched on
        if( $this->useSession() )
        {
            // store the required flag for easy access
            $tempdir	=	$this->attributes['tempdir'];
            
            // use Linux default
            if( empty( $tempdir ) ) {
            	$tempdir	=	'/tmp';
            }
            
            if( !is_dir( $tempdir ) && !is_writeable( $tempdir ) )
            {
                return patErrorManager::raiseError( PATFORMS_FILE_ERROR_TEMPDIR_NOT_VALID,
                                                'Session support is on but cannot work',
                                                'Temporary directory "' . $tempdir . '" does not exist or is not writeable!' );
            }
            
            $this->tempdir	=	$tempdir;
        }
    
    	return true;
    }

   /**
	* element creation method for the 'HTML' format in the 'default' form mode.
	*
	* @access	public
	* @param	mixed	value of the element
	* @return	mixed	$element	The element, or false if failed.
	* @todo		check, why the value has to be stored in the attributes
	*/
	function serializeHtmlDefault( $value )
	{
		$this->attributes['value']	=	$value;
		$name		=	$this->attributes['name'];
		$nameUpload	=	$name . '_upload';

		// make sure we're a file field :)
		$this->attributes['type']	=	'file';

		// editable or not?
		if( isset( $this->attributes['edit'] ) && $this->attributes['edit'] == 'no' )
		{
			return $this->serializeHtmlReadonly( $value );
		}

		// create element
		$this->attributes['name']	=	$nameUpload;
		$element = $this->toHtml();
		if( patErrorManager::isError( $element ) )
		{
			return $element;
		}

		$this->attributes["name"]	=	$name;
		$hidden =	$this->createHiddenTag( $this->attributes["value"] );

		// and return to sender...
		return $hidden . "\n" . $element;
	}

   /**
	* element creation method for the 'HTML' format in the 'readonly' form mode.
	* Very simple; just returns the stored element value.
	*
	* @access	public
	* @param	mixed	value of the element
	* @return	string	$value	The element's value
	*/
	function serializeHtmlReadonly( $value )
	{
		$display	=	$value;

		$this->getAttributesFor( $this->getFormat() );

		return $display . $this->createHiddenTag( $value );
	}

   /**
	* validates the element.
	*
	* @access	public
	* @param	mixed	value of the element
	* @return	bool	$isValid	True if element could be validated, false otherwise.
	*/
	function validateElement( $value )
	{
		$name		=	$this->attributes['name'];
		$nameUpload	=	$name . '_upload';

		$empty		=	false;
		$mime		=	false;

		$error		=	UPLOAD_ERR_NO_FILE;
		$restored	=	false;

		$files = $_FILES;
		if (($namespace = $this->getNamespace()) && isset($files[$namespace])) {
			$files = $files[$namespace];
		}
		
		// check destination dir
		if( !is_dir( $this->attributes['uploaddir'] ) || !is_writeable( $this->attributes['uploaddir'] ) ) {
				return patErrorManager::raiseError( PATFORMS_FILE_ERROR_UPLOAD_DIR_NOT_WRITEABLE ,
																				'Upload folder invalid',
																				'Destination folder "' . $this->attributes['uploaddir'] . '" does not exist or is not writeable!' );
		}

		// file was just uploaded
		if( isset( $files ) && (isset($files[$nameUpload]) || isset($files['name'][$nameUpload])))
		{
			if (!$namespace) {
				$uploadFile	=	$files[$nameUpload]['tmp_name'];
				$error		=	$files[$nameUpload]['error'];
				$mime		=	$files[$nameUpload]['type'];
				$value		=	$this->_replaceChars($files[$nameUpload]['name']);
				$size		=	$files[$nameUpload]['size'];
			} else {
				$uploadFile	=	$files['tmp_name'][$nameUpload];
				$error		=	$files['error'][$nameUpload];
				$mime		=	$files['type'][$nameUpload];
				$value		=	$this->_replaceChars($files['name'][$nameUpload]);
				$size		=	$files['size'][$nameUpload];
			}

			// see if the value comes from "setValue()" instead
			if( empty( $value ) && !empty( $this->value ) )
			{
				if( $this->useSession() )
				{
					// already stored in session
					$state		=	$this->getSessionValue( 'state' );
					if( $state == 'valid' )
					{
						return true;
					}
				}

				/*
				$dir	=	$this->attributes['uploaddir'];
				$mime	=	mime_content_type( $dir . '/' . $this->value );
				if( !$this->_checkMimeType( $mime ) )
				{
					return false;
				}
				*/

				if( $this->useSession() )
				{
					$this->setSessionValue( 'state', 'finalized' );
				}
				return true;
			}

			// don't allow empty files
			if( !$error && !$size )
			{
				$this->addValidationError( 6 );
				return false;
			}

			// new file -> must be valid
			if( $this->useSession() )
			{
				$this->setSessionValue( 'state', 'invalid' );
			}

			// remove previous uploaded file
			if( $oldfile = $this->getSessionValue( 'uploadfile' ) )
			{
				unlink( $oldfile );
			}

			if( !$this->_checkErrorCode( $error ) )
			{
				return false;
			}

			// validate file-type

			if( !$this->_checkMimeType( $mime, $value ))
			{
				return false;
			}
		}
		// see if file was uploaded during this session
		else if( $this->useSession() )
		{
			$uploadFile	=	$this->getSessionValue( 'uploadfile' );
			$state		=	$this->getSessionValue( 'state' );
			$mime		=	$this->getSessionValue( 'mime' );

			if( $sessValue = $this->getSessionValue( 'name' ) )
			{
				$value	=	$sessValue;
			}

			// all done, skip the rest
			if( $state == 'finalized' )
			{
				$this->value		=	$value;
				return true;
			}

			$exists	=	$this->_fileExists( $value );
			if( patErrorManager::isError( $exists ) )
			{
				return $exists;
			}

			if( $exists )
			{
				return true;
			}

			if( $state == 'valid' || file_exists( $uploadFile ) )
			{
				$restored	=	true;
				$error		=	UPLOAD_ERR_OK;
			}

			if( !$this->_checkErrorCode( $error ) )
			{
				return false;
			}
		}
		// value can also be set from outsite by setValue()
		else
		{
			$exists	=	$this->_fileExists( $value );
			if( patErrorManager::isError( $exists ) )
			{
				return $exists;
			}
			else if( $exists )
			{
				return true;
			}
		}

		// not required
		if( empty( $value ) && $this->attributes['required'] != 'yes' )
		{
			return true;
		}

		$checkDir	=	$this->_checkDestination();
		if( patErrorManager::isError( $checkDir ) )
		{
			return $checkDir;
		}

		// check if file exists
		$dir	=	$this->attributes['uploaddir'];
		if( $this->attributes['overwrite'] != 'yes' )
		{
			if( file_exists( $dir . '/' . $value ) )
			{
				$this->addValidationError( 2 );
				return false;
			}
		}

		// keep uploaded file
		if( $this->useSession() && !$restored )
		{
			$tempfile	=	tempnam( $this->tempdir, $name . '_' );
			if( !move_uploaded_file( $uploadFile, $tempfile ) )
			{
				printr("Could not move file '". $uploadFile ."' to '" . $tempfile . "'");
				
				return patErrorManager::raiseError( PATFORMS_FILE_ERROR_CANNOT_MOVE_UPLOAD_FILE,
							"Cannot upload file",
							"Could not move file '". $uploadFile ."' to '$dir/$value'" );
			}
			chmod( $tempfile, $this->_getPermission() );

			$this->setSessionValue( "uploadfile", $tempfile );
			$this->setSessionValue( "name", $value );
			$this->setSessionValue( "state", "valid" );
		}

		$this->value		=	$value;

		return true;
	}

   /**
	* finalize file upload
	*
	* Move file to upload directory
	*
	* @access public
	* @param	mixed	value of the element
	* @return boolean $result true on success
	*/
    function finalizeElement( $value )
    {
		$name		=	$this->attributes["name"];
		$nameUpload	=	$name . "_upload";
		$dir		=	$this->attributes["uploaddir"];

		$files = $_FILES;
		if (($namespace = $this->getNamespace()) && isset($files[$namespace])) {
			$files = $files[$namespace];
		}

		$uploadFile	=	false;
		if( $this->useSession() )
		{
			// check if this upload was finalized
			$state	=	$this->getSessionValue( "state" );
			if( $state == "finalized" )
			{
				return true;
			}

			$uploadFile	=	$this->getSessionValue( "uploadfile" );
			$this->unsetSessionValue( "uploadfile" );
			$this->unsetSessionValue( "name" );
			$this->setSessionValue( "state", "finalized" );
		}
		else if( isset( $files[$nameUpload]["tmp_name"] ) )
		{
			$uploadFile	=	$files[$nameUpload]["tmp_name"];
		}
		else if( isset( $files["tmp_name"][$nameUpload] ) )
		{
			$uploadFile	=	$files["tmp_name"][$nameUpload];
		}

		// if file does not exist and is not required, function will just return
		if( $this->attributes["required"] != "yes" && !$uploadFile )
		{
			return true;
		}

		// cannot use 'move_upload_file()' in case of usage of sessions
		if( !rename( $uploadFile, "$dir/$value" ) )
		{
			printr("Could not move file '". $uploadFile ."' to '$dir/$value'");
			return patErrorManager::raiseError( PATFORMS_FILE_ERROR_CANNOT_MOVE_UPLOAD_FILE,
						"Cannot upload file",
						"Could not move file '". $uploadFile ."' to '$dir/$value'" );
		}
		chmod( "$dir/$value", $this->_getPermission() );
		return true;
    }
   /**
    * check the given code if it is an error
	*
	* @access private
	* @param int $code
	* @return boolean $result true on success
	*/
	function _checkErrorCode( $code )
	{
		switch( $code )
		{
			// all good
			case	UPLOAD_ERR_OK:
				break;

			// file to large
			case	UPLOAD_ERR_INI_SIZE:
			case	UPLOAD_ERR_FORM_SIZE:
				$this->addValidationError( 3, array( 'maxsize' => ini_get( 'upload_max_filesize' ) . 'B' ) );
				return false;
				break;

			// file has only partial arrived
			case	UPLOAD_ERR_PARTIAL:
				$this->addValidationError( 4 );
				return false;
				break;

			// no file send
			case	UPLOAD_ERR_NO_FILE:
				if( $this->attributes['required'] == 'yes' )
				{
					$this->addValidationError( 1 );
					return false;
				}
				return true;
				break;

			// this should never happen
			default:
				break;
		}

		return true;
	}


   /**
	* check if file type is one of the allowed
	*
	* @access private
	* @param string	$type mimetype of the file
	* @return boolean $result true if mimetype is allowed
	*/
    function _checkMimeType( $type, $value='')
    {
		if( empty( $this->attributes["mimetype"] ) || ($this->attributes['required'] == 'no') && empty($value))
		{
	        return true;
		}

		// check for exact match
		if( in_array( $type, $this->attributes["mimetype"] ) )
		{
			return true;
		}

		// check for *-pattern like image/*
		list( $major )	=	explode( "/", $type );
		foreach( $this->attributes["mimetype"] as $m )
		{
			$m =	explode( "/", $m );
			if( $m[1] == "*" && $m[0] == $major )
			{
				return true;
			}
		}

		$this->addValidationError( 5, array( "mimetype" => $type ) );
		return false;
    }

   /**
	* replace invalid characters in filename
	*
	* @access private
	* @param string $name filename
	* @return string $result filename
	*/
    function _replaceChars( $name )
    {
		$preg	=	explode( "/", $this->attributes["replacename"] );

		if( count( $preg ) != 3 )
		{
			return patErrorManager::raiseError( PATFORMS_FILE_ERROR_REPLACE_PREG_INVALID,
						"Attribute 'replace' is not a valid PREG",
						"Wrong syntax in '" . $this->attributes["replace"] . "'. Format must be 'modifier/search/replace', see manual page 'perlreg' and the manual for the PHP-function preg_replace" );
		}

		return preg_replace( "/". $preg[1] . "/" . $preg[0], $preg[2], $name );
    }

   /**
	* check upload dir
	*
	* @access private
	* @return boolean $result true on success, patError-object on error!
	*/
    function _checkDestination()
    {
		// check destination
		if( !strlen( $this->attributes["uploaddir"] ) )
		{
			printr("Upload directory is not set");
			
				return patErrorManager::raiseError(  PATFORMS_FILE_ERROR_NO_FILE_UPLOAD_DIR,
								"Cannot upload file",
								"Upload directory is not set" );
		}

		$dir	=	$this->attributes["uploaddir"];
		if( !is_dir( $dir ) )
		{
			printr("Upload directory '$dir' is not directory");
			
			return patErrorManager::raiseError(  PATFORMS_FILE_ERROR_NO_FILE_UPLOAD_DIR,
							"Cannot upload file",
							"Upload directory '$dir' is not directory" );
		}

		if( !is_writeable( $dir ) )
		{
			printr("Upload directory '$dir' is not writeable");
			
			return patErrorManager::raiseError( PATFORMS_FILE_ERROR_UPLOAD_DIR_NOT_WRITEABLE,
							"Cannot upload file",
							"Upload directory '$dir' is not writeable" );
		}

		return true;
	}

   /**
	* checks whether file exists in upload dir or not
	*
	* @access private
	* @param string $file name of file
	* @return boolean $result true if file exists
	*/
    function _fileExists( $file )
    {
		if( !$file && !strlen( $file ) )
		{
			return false;
		}

		$result	=	$this->_checkDestination();
		if( patErrorManager::isError( $result ) )
		{
			return $result;
		}

		$dir	=	$this->attributes["uploaddir"];
		if( file_exists( $dir . "/" . $file ) )
		{
			return true;
		}
    }

   /**
	* calculates the file permissions from permissions-string
	*
	* @access private
	* @return int permission value ready to use with chmod()
	* @see $permission
	*/
    function _getPermission()
    {
		if( $this->permission != null )
		{
			return $this->permission;
		}

		$perm	=	$this->attributes['permissions'];

		// permission string must be contain four letters, e.g. 0644
		if( strlen( $perm ) != 4 )
		{
			patErrorManager::raiseError( 	PATFORMS_FILE_ERROR_PERMISSION_ATTRIBUTE_NOT_VALID,
											"Wrong format of attribute 'permissions'",
											"The attribute 'permissions' must be a 4-letter string, e.g. '0644'"
										);
		}

		if( !is_numeric( $perm ) )
		{
			patErrorManager::raiseError( 	PATFORMS_FILE_ERROR_PERMISSION_ATTRIBUTE_NOT_VALID,
											"Wrong format of attribute 'permissions'",
											"The attribute 'permissions' contains unknwon characters."
										);
		}

		if( $perm[0] != '0' )
		{
			patErrorManager::raiseError( 	PATFORMS_FILE_ERROR_PERMISSION_ATTRIBUTE_NOT_VALID,
											"Wrong format of attribute 'permissions'",
											"The attribute 'permissions' must start with '0', e.g. '0644'"
										);
		}

		// skip leading zero
		$this->permission	=	0;
		$this->permission	+=	$perm[3];
		$this->permission	+=	$perm[2] * 8;
		$this->permission	+=	$perm[1] * 64;
		return $this->permission;
    }
}
?>