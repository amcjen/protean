<?PHP
/**
 * class to parse an HTML document or patTemplate and extract all
 * patForms elements. They will be replaced by placeholders.
 *
 * $Id: Parser.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @package		patForms
 * @subpackage	Parser
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */

/**
 * file does not exist
 */
define( 'PATFORMS_PARSER_ERROR_FILE_NOT_FOUND', 100000 );
 
/**
 * file could not be created
 */
define( 'PATFORMS_PARSER_ERROR_FILE_NOT_CREATED', 100001 );

/**
 * element cannot be serialized
 */
define( 'PATFORMS_PARSER_ERROR_ELEMENT_NOT_SERIALIZEABLE', 100002 );

/**
 * element cannot be serialized
 */
define( 'PATFORMS_PARSER_ERROR_CACHEDIR_NOT_VALID', 100003 );

/**
 * no namespace has been declared
 */
define( 'PATFORMS_PARSER_ERROR_NO_NAMESPACE', 100004 );

/**
 * static property does not exist
 */
define( 'PATFORMS_PARSER_ERROR_NO_STATIC_PROPERTY', 100005 );

/**
 * basedir is not valid
 */
define( 'PATFORMS_PARSER_ERROR_BASEDIR_NOT_VALID', 100006 );

/**
 * no closing tag found
 */
define( 'PATFORMS_PARSER_ERROR_NO_CLOSING_TAG', 100007 );

/**
 * invalid tag found
 */
define( 'PATFORMS_PARSER_ERROR_INVALID_CLOSING_TAG', 100008 );

/**
 * invalid tag found
 */
define( 'PATFORMS_PARSER_ERROR_DRIVER_FILE_NOT_FOUND', 100009 );

/**
 * invalid tag found
 */
define( 'PATFORMS_PARSER_ERROR_DRIVER_CLASS_NOT_FOUND', 100010 );

/**
 * form does not exist
 */
define( 'PATFORMS_PARSER_ERROR_FORM_NOT_FOUND', 100011 );

/**
 * unknown tag in custom namespace
 */
define('PATFORMS_PARSER_ERROR_UNKNOWN_TAG', 100020);

/**
 * static properties
 * @var 	array
 * @access	private
 */  
$GLOBALS['_patForms_Parser']	=	array( 
										'cacheFolder'				=>	false,
										'baseDir'					=>	false,
										'namespace'					=>	false,
										'placeholder'				=>	'{PATFORMS_ELEMENT_%s}',
										'placeholder_form_start'	=>	'{PATFORMS_FORM_%s_START}',
										'placeholder_form_end'		=>	'{PATFORMS_FORM_%s_END}',
										'placeholder_case'			=>	'upper',
										'namespaceHandlers'			=>	array()
									);


/**
 * class to parse an HTML document or patTemplate and extract all
 * patForms elements. They will be replaced by placeholders.
 *
 * It is possible to attach handlers for other namespaces.
 * The parser will delegate the tags to these handlers and
 * the return values will be used to create the form instead.
 *
 * Known issues of the parser:
 * - Currently it's only possible to parse one form per document, this will change in future versions
 *
 * @author		Stephan Schmidt <s.schmidt@metrix.de>
 * @package		patForms
 * @subpackage	Parser
 * @license		LGPL
 * @copyright	PHP Application Tools <http://www.php-tools.net>
 */
class patForms_Parser
{
   /**
	* Stores the names of all static properties that patForms_Parser will use as defaults
	* for the properties with the same name on startup.
	*
	* @access	private
	*/
	var $staticProperties	=	array(
		'cacheFolder'	=>	'setCacheDir',
		'baseDir'		=>	'setBaseDir',
		'namespace'		=>	'setNamespace',
	);

   /**
	* namespace for form elements
	* @var		string
	* @access	private
	*/
	var $_namespace			=	null;

   /**
	* namespace handlers
	* @var		string
	* @access	private
	*/
	var $_namespaceHandlers	=	array();

   /**
	* cache folder
	* @var		string
	* @access	private
	*/
	var $_cacheFolder	=	null;

   /**
	* base directory
	* @var		string
	* @access	private
	*/
	var $_baseDir	=	null;

   /**
	* placeholder template for form elements
	*
	* %s will be replaced with the name of the element
	*
	* @var		string
	* @access	private
	* @see		$_placeholder_case
	*/
	var $_placeholder = '{PATFORMS_ELEMENT_%s}';

   /**
	* placeholder template for start of form
	*
	* %s will be replaced with the name of the form
	*
	* @var		string
	* @access	private
	* @see		$_placeholder_case
	* @see		$_placeholder_form_end
	*/
	var $_placeholder_form_start = '{PATFORMS_FORM_%s_START}';

   /**
	* placeholder template for start of form
	*
	* %s will be replaced with the name of the form
	*
	* @var		string
	* @access	private
	* @see		$_placeholder_case
	* @see		$_placeholder_form_start
	*/
	var $_placeholder_form_end = '{PATFORMS_FORM_%s_END}';

   /**
	* case of the element name in the template
	*
	* @var		string
	* @access	private
	* @see		$_placeholder
	*/
	var $_placeholder_case = 'upper';

   /**
	* sourcefile name
	* @var		string
	* @access	private
	*/
	var $_sourceFile;

   /**
	* outputfile name
	* @var		string
	* @access	private
	*/
	var $_outputFile;

   /**
	* form object
	* @var	object
	* @access	private
	*/
	var $_form;
	
   /**
	* name of the current form
	* @var		string
	* @access	private
	*/
	var $_currentForm = '__default';

   /**
	* form element definitions
	* @var	array
	* @access	private
	*/
	var $_elementDefinitions = array();

   /**
	* form attributes
	* @var	array
	* @access	private
	*/
	var $_formAttributes = array();
	
   /**
	* HTML code
	* @access	private
    * @var      string
	*/
	var $_html;

   /**
	* elements found during parsing process
	* @access	private
	* @var	array
	*/
	var	$_elStack = array();

   /**
	* cdata found during parsing process
	* @access	private
	* @var	array
	*/
	var	$_cData	= array();

   /**
	* tag depth
	* @access	private
	* @var	integer
	*/
	var	$_depth	=	0;

   /**
	* entities that may be used in attributes
	* @var		array
	* @access	private
	*/
	var $_entities	=	array(
								'&quot;' => '"',
								'&amp;'  => '&',
								'&apos;' => '\'',
								'&gt;'   => '>',
								'&lt;'   => '<',
							);

   /**
	* constructor
	*
	* @access	public
	*/
	function patForms_Parser()
	{
		$this->__construct();	
	}
	
   /**
	* constructor
	*
	* @access	public
	*/
	function __construct()
	{
		foreach ($this->staticProperties as $staticProperty => $setMethod) {
			$propValue = patForms_Parser::getStaticProperty( $staticProperty );
			if (patErrorManager::isError($propValue)) {
				continue;
			}
			
			$this->$setMethod($propValue);
		}

		/**
		 * set the placeholders
		 */
		$this->setPlaceholder( patForms_Parser::getStaticProperty( 'placeholder' ), patForms_Parser::getStaticProperty( 'placeholder_case' ) );
		$this->setFormPlaceholders( patForms_Parser::getStaticProperty( 'placeholder_form_start' ), patForms_Parser::getStaticProperty( 'placeholder_form_end' ) );
		
		// configure namespace handler
		$nsHandlers	= &patForms_Parser::getStaticProperty('namespaceHandlers');
		$namespaces	= array_keys( $nsHandlers );
		foreach ($namespaces as $ns) {
			$this->addNamespace( $ns, $nsHandlers[$ns] );
		}
	}
	
   /**
	* setCacheDir
	*
	* The cache dir has to be set to utilize the caching features.
	*
	* @access	public
	* @param	mixed		path to the directory or false to disable caching
	* @return	boolean		true on success
	* @see	$_cacheFolder
	*/
    function setCacheDir( $dir )
    {
		if ($dir != false) {
			if (!is_dir($dir) || !is_writable($dir)) {
				return patErrorManager::raiseError(
					PATFORMS_PARSER_ERROR_CACHEDIR_NOT_VALID,
					"Cache folder '$dir' is either no directory or not writable.",
					'Check path and permissions'
				);
			}
		}

		if (isset($this) && is_a($this, 'patForms_Parser')) {
			$this->_cacheFolder	= $dir;
		} else {
			patForms_Parser::setStaticProperty('cacheFolder', $dir);
		}
        return  true;
    }
	
	/**
	 * Set base directory for all files.
	 *
	 * @access	public
	 * @param	mixed	path to the directory or false reset the basedir
	 * @return boolean $result	true on success
	 * @see	$_cacheFolder
	 */
    function setBaseDir( $dir )
    {
		if( $dir != false )
		{
			if( !is_dir( $dir ) )
			{
				return patErrorManager::raiseError(
					PATFORMS_PARSER_ERROR_BASEDIR_NOT_VALID,
					"Base directory '$dir' is does not exist or is no directory"
				);
			}
		}
		
		if( isset( $this ) && is_a( $this, "patForms_Parser" ) )
		{
			$this->_baseDir	=	$dir;
		}
		else
		{
			patForms_Parser::setStaticProperty( "baseDir", $dir );
		}
		
        return  true;
    }
	
   /**
	* Set the namespace for the form elements
	*
	* If this method is called statically, it will set the
	* namespace for future static calls to createFormFromTemplate()
	* and parseFile().
	*
	* If the namespace is set to null, patForms_Parser will try
	* to get the namespace declaration from the (X)HTML document
	* that is being parsed.
	*
	* That means that you should include a 
	* xmlns:myForm="http://www.php-tools.net/patForms/basic"
	* attribute in the root tag of your templates.
	* "myForm" is the namespace that you are using for your
	* patForms elements. Make sure that you are using the
	* correct URI for the attribute so it can be recognized.
	*
	* @access	public
	* @param	string	namespace
	* @see		getNamespacePrefix()
	*/
	function setNamespace( $ns )
	{
		if( isset( $this ) && is_a( $this, "patForms_Parser" ) )
		{
			$this->_namespace	=	$ns;
		}
		else
		{
			patForms_Parser::setStaticProperty( "namespace", $ns );
		}
	}
	
   /**
	* Set the placeholder template for elements.
	*
	* When parsing an HTML page that contains patForms elements
	* they will be replaced by placeholders. This method allows
	* you to set the format of the placeholders.
	* 
	* You may specify a format string like you would for
	* sprintf, with one %s that marks where the name of the
	* element will be inserted.
	*
	* @access	public
	* @param	string		placeholder
	* @param	string		flag to indicate, whether the name should be inserted in uppercase ('upper'),
	*						lowercase ('lower') or how it was specified ('keep').
	* @see		sprintf()
	* @see		setFormPlaceholders()
	*/
	function setPlaceholder( $placeholder, $case = "upper" )
	{
		if( isset( $this ) && is_a( $this, "patForms_Parser" ) )
		{
			$this->_placeholder			=	$placeholder;
			$this->_placeholder_case	=	$case;
		}
		else
		{
			patForms_Parser::setStaticProperty( "placeholder", $placeholder );
			patForms_Parser::setStaticProperty( "placeholder_case", $case );
		}
	}
	
   /**
	* Set the placeholder template for form start and end tag.
	*
	* When parsing an HTML page that contains a patForms:Form tag
	* this will be replaced by placeholders. This method allows
	* you to set the format of the placeholders.
	* 
	* You may specify a format string like you would for
	* sprintf, with one %s that marks where the name of the
	* element will be inserted.
	*
	* @access	public
	* @param	string		placeholder for the start tag
	* @param	string		placeholder for the end tag
	* @see		sprintf()
	* @see		setPlaceholder()
	*/
	function setFormPlaceholders( $start, $end )
	{
		if( isset( $this ) && is_a( $this, "patForms_Parser" ) )
		{
			$this->_placeholder_form_start	=	$start;
			$this->_placeholder_form_end	=	$end;
		}
		else
		{
			patForms_Parser::setStaticProperty( "placeholder_form_start", $start );
			patForms_Parser::setStaticProperty( "placeholder_form_end", $end );
		}
	}
	
   /**
	* add a namespace handler
	*
	* Namespace handlers can be used to include external
	* data in patForms elements.
	*
	* @access	public
	* @param	string	namespace
	* @param	object	handler
	*/
	function addNamespace( $namespace, &$handler )
	{
		if( isset( $this ) && is_a( $this, "patForms_Parser" ) )
		{
			$this->_namespaceHandlers[$namespace]	=&	$handler;
		}
		else
		{
			$ns	=&	patForms_Parser::getStaticProperty( 'namespaceHandlers' );
			$ns[$namespace]	=&	$handler;
		}
	}

   /**
	* parse a file and extract all elements
	*
	* If an outputfile is specified and the cache directory
	* has been set, two files will be created:
	* - the outputfile, containing the HTML code with placeholders for all elements
	* - a cache file that contains a serialized string with the attributes of all elements
	*
	* On the next call to parseFile() patForms_Parser will check
	* if these files exist and use them instead of parsing the sourcefile.
	*
	* @access	public
	* @param	string	$filename	filename
	* @return	boolean
	* @uses		parseString()
	*/
	function parseFile( $filename, $outputFile = null )
	{
		$this->_sourceFile	=	$filename;
		$this->_outputFile	=	$outputFile;

		if ($this->_outputFile != null) {
			$cache = $this->_checkCache();
			if ($cache) {
				return true;
			}
		}
				
		$string	= file_get_contents( $this->_adjustFilename( $this->_sourceFile ) );
		if ($string === false) {
			$relative = '(no basedir set)';
			if( !empty( $this->_baseDir ) ) {
				$relative = '(relative to "'.$this->_baseDir.'")';
			}
			
			return patErrorManager::raiseError(
				PATFORMS_PARSER_ERROR_FILE_NOT_FOUND, 
				'Sourcefile could not be read', 
				'Tried to open file "'.$this->_sourceFile.'" '.$relative
			);
		}

		$result	= $this->parseString($string);

		if ($this->_outputFile != null && $this->_cacheFolder != null) {
			$success = $this->_writeHTMLToFile();
			if (patErrorManager::isError($success)) {
				return $success;
			}
				
			$success = $this->_writeFormToFile();
			if (patErrorManager::isError($success)) {
				return $success;
			}
		}
		return	$result;
	}

   /**
    * write the resulting HTML code to a file
	*
	* @access	private
	*/
	function _writeHTMLToFile()
	{
		return	$this->_writeToFile( $this->_adjustFilename( $this->_outputFile ), $this->getHTML() );
	}
	
   /**
    * write the form defintions to a cache file
	*
	* @access	private
	*/
	function _writeFormToFile()
	{
		$tmp		=	array(
								'attributes'	=>	$this->_formAttributes,
								'elements'		=>	$this->_elementDefinitions
							);
		$formDef	=	serialize( $tmp );
		$cacheFile	=	$this->_getFormCacheFilename();
		return	$this->_writeToFile( $cacheFile, $formDef );
	}

   /**
	* get the filename for the form cache
	*
	* @access	private
	* @return	string
	*/
	function _getFormCacheFilename()
	{
		return	$this->_cacheFolder . "/" . md5( $this->_sourceFile ) . ".form";
	}

   /**
    * adjust a filename according to the specified basedir
	*
	* @access	private
	* @param	string		filename
	* @param	string		adjusted filename
	*/
	function _adjustFilename( $filename )
	{
		if( !empty( $this->_baseDir ) )
			return	"{$this->_baseDir}/$filename";
		return $filename;
	}
	
   /**
	* write a string to a file
	*
	* I dreamed of a world, where PHP5 has already been declared as
	* stable and I could just use file_put_contents() for such an easy
	* task.
	*
	* @access	private
	* @param	string	$filename
	* @param	string	$data
	*/
	function _writeToFile( $file, $data )
	{
		$fp = @fopen( $file, "w" );
		if (!$fp) {
			return patErrorManager::raiseError(
				PATFORMS_PARSER_ERROR_FILE_NOT_CREATED, 
				"Could not create file '$file'.", 
				"File: " . $file 
			);
		}
		flock($fp, LOCK_EX);
		fwrite($fp, $data);
		flock($fp, LOCK_UN);
		fclose($fp);
		return true;
	}
	
	
   /**
	* check, whether a cache can be used
	*
	* @access	private
	* @return	boolean		true, if the cache exists and still is valid, false otherwise
	*/
	function _checkCache()
	{
		if( empty( $this->_cacheFolder ) )
			return	false;
			
		if( !file_exists( $this->_outputFile ) )
			return	false;

		$cacheFile	=	$this->_getFormCacheFilename();
			
		if( !file_exists( $cacheFile ) )
			return	false;

		$srcFile	=	$this->_adjustFilename( $this->_sourceFile );
		$srcTime	=	filemtime( $srcFile );
			
		if( filemtime( $this->_outputFile ) < $srcTime )
			return	false;

		if( filemtime( $cacheFile ) < $srcTime )
			return	false;

		$form = file_get_contents( $cacheFile );
		if ($form === false) {
			return false;
		}

		$tmp = unserialize($form);
		$this->_formAttributes		=	$tmp['attributes'];
		$this->_elementDefinitions	=	$tmp['elements'];
		return	true;
	}
	
   /**
	* parse a string and extract all elements
	*
	* @access	public
	* @param	string	$string		html string that should be parsed
	* @return	boolean
	*/
	function parseString( $string )
	{
		$this->_elStack	=	array();
		$this->_cData	=	array();
		$this->_depth	=	0;

		// has namespace been set?
		if( $this->_namespace == null )
		{
			$ns	=	$this->getNamespacePrefix( $string );
			if( patErrorManager::isError( $ns ) )
			{
				return	$ns;
			}
			$this->setNamespace( $ns );
		}

		$knownNamespaces	=	array_merge( array( $this->_namespace ), array_keys( $this->_namespaceHandlers ) );

		$regexp	=	"/(<(\/?)([[:alnum:]]+):([[:alnum:]]+)[[:space:]]*([^>]*)>)/im";

		$tokens	=	preg_split( $regexp, $string, -1, PREG_SPLIT_DELIM_CAPTURE );

		/**
		 * the first token is always character data
		 * Though it could just be empty
		 */
		if( $tokens[0] != '' )
			$this->_characterData( $tokens[0] );
		
		$cnt	=	count( $tokens );
		$i		=	1;
		// process all tokens
		while( $i < $cnt )
		{
			$fullTag	=	$tokens[$i++];
			$closing	=	$tokens[$i++];
			$namespace	=	$tokens[$i++];
			$tagname	=	$tokens[$i++];
			$attString	=	$tokens[$i++];
			$empty		=	substr( $attString, -1 );
			$data		=	$tokens[$i++];

			 // check, whether it's a known namespace
			 if (!in_array($namespace, $knownNamespaces)) {
			 	$this->_characterData($fullTag);
			 	$this->_characterData($data);
				continue;
			 }
			
			// is it a closing tag?
			if ($closing == "/") {
				$result	= $this->_endElement( $namespace, $tagname );
				if (patErrorManager::isError($result)) {
					return $result;
				}
				$this->_characterData( $data );
				continue;
			}

			// Is empty or opening tag!
			$attributes = $this->_parseAttributes($attString);
			$result = $this->_startElement( $namespace, $tagname, $attributes );
			if (patErrorManager::isError($result)) {
				return	$result;
			}

			// check, if the tag is empty
			if ($empty == '/') {
				$result	= $this->_endElement($namespace, $tagname);
				if (patErrorManager::isError($result)) {
					return $result;
				}
			}

			$this->_characterData($data);
		}
		
		// check for tags that are still open
		if ($this->_depth > 0) {
			$el	= array_pop($this->_elStack);
			return patErrorManager::raiseError(
				PATFORMS_PARSER_ERROR_NO_CLOSING_TAG,
				"No closing tag for {$el['ns']}:{$el['name']} found." 
			);
		}
		return	true;
	}

   /**
	* parse an attribute string and build an array
	*
	* @access	private
	* @param	string	attribute string
	* @param	array	attribute array
	*/
	function _parseAttributes( $string )
	{
		//	Check for trailing slash, if tag was an empty XML Tag
		if( substr( $string, -1 ) == "/" )
			$string	=	trim( substr( $string, 0, strlen( $string )-1 ) );

		$attributes	=	array();
		preg_match_all('/([a-zA-Z_\:]+)="((?:\\\.|[^"\\\])*)"/', $string, $match);
		for ($i = 0; $i < count($match[1]); $i++)
		{
			$attributes[strtolower( $match[1][$i] )] = strtr( (string)$match[2][$i], $this->_entities );
		}
		return	$attributes;
	}

   /**
	* start element handler
	*
	* @access	private
	* @param	string		namespace
	* @param	string		local part
	* @param	array		attributes
	* @see		endElement()
	*/
	function _startElement( $ns, $name, $attributes )
	{
		array_push( $this->_elStack, array(
											"ns"			=>	$ns,
											"name"			=>	$name,
											"attributes"	=>	$attributes,
											"hasChildren"	=>	false
										)
				 );

		switch ($name) {
			// Build a form
			case 'Form':
				if (isset($attributes['name'])) {
					$this->_currentForm = $attributes['name'];
				}
				$this->_formAttributes[$this->_currentForm]	= $attributes;
				$this->_characterData( $this->_getPlaceholderForForm( $attributes['name'], 'start' ) );
				break;
		}		
		$this->_depth++;

		$this->_data[$this->_depth]	=	'';
	}

   /**
	* end element handler
	*
	* @access	private
	* @param	string		namespace
	* @param	string		local part
	* @uses		addElementDefinition() to add an element to the form
	* @uses		_callNamespaceHandler() to delegate callback to a handler
	*/
	function _endElement($ns, $name)
	{
		$el = array_pop($this->_elStack);
		$data = $this->_getCData();
		$this->_depth--;

		if ($el["name"] != $name || $el["ns"] != $ns) {
			return patErrorManager::raiseError(
				PATFORMS_PARSER_ERROR_INVALID_CLOSING_TAG,
				"Invalid closing tag {$ns}:{$name}. {$el['ns']}:{$el['name']} expected." 
			);
		}

		/**
		 * Foreign namespace has been found
		 * delegate it to the namespace handler
		 */
		if ($ns != $this->_namespace) {
			$result = $this->_callNamespaceHandler( $ns, $name, $el["attributes"], $data );
			if ($this->_depth > 0) {
				$this->_addToParentTag($result);
			} elseif (is_string( $result)) {
				$this->_characterData($result);
			}
			return true;
		}

		switch ($name) {
		    // Build a form
			case 'Form':
				$this->_characterData($data);
				$this->_currentForm = '__default';
				$this->_characterData($this->_getPlaceholderForForm($el['attributes']['name'], 'end'));
				break;

			// Add an option to an Enum element
			case 'Option':
				$parent = array_pop($this->_elStack);
				array_push($this->_elStack, $parent);

				$parentName = strtolower( $parent['name'] );
				
				if (isset($el['attributes']['value'])) {
					if (!isset($el['attributes']['id'])) {
						if (!isset($parent['children'])) {
							$parent['children'] = array();
						}
						$cnt = count($parent['children']) + 1;
						
						$el['attributes']['id'] = $parent['attributes']['name'] . '_option' . $cnt;
					}

    				$label  = isset($el['attributes']['label']) ? $el['attributes']['label'] : $data;
    				$id     = isset($el['attributes']['id']) ? $el['attributes']['id'] : $el['attributes']['value'];
					$option	= $el['attributes'];
					
					$option['label'] = $label;
					$option['id']    = $id;
				} else {
					$option = $data;
				}
				
				$this->_addToParentTag($option, null, true);

                // if it is an option of a radio group, add a placeholder
				if ($parentName == 'radiogroup') {
					$this->_characterData('{PATFORMS_ELEMENT_'.strtoupper( $parent['attributes']['name'] ).'_' . strtoupper( $el['attributes']['id'] ) . '}');
				}
				break;

			// Use a datasoucre
			case 'Datasource':
				switch (strtolower($el['attributes']['type'])) {
					// Datasource is a callback
					// This can either be a function or a static method
					case 'callback':
						
						// get method from method or function attribute
						if (isset($el['attributes']['method'])) {
							$method	= $el['attributes']['method'];
						} elseif (isset($el['attributes']['function'])) {
							$method = $el['attributes']['function'];
                        } else {
							return patErrorManager::raiseError(PATFORMS_PARSER_ERROR_UNKNOWN_TAG, 'No function or method has been specified as callback');
                        }

						if (isset( $el['attributes']['class'])) {
							$datasource	= array( $el['attributes']['class'], $method );
						} else {
							$datasource	= $method;
						}
						break;
						
					// add a custom datasource
					case 'custom':
						$datasource	= $el['children'];
						break;

				}
				$this->_addToParentAttributes('datasource', $datasource);
				break;

			// Set any attribute
			case 'Attribute':
				if (isset($el['children'])) {
					$data = $el['children'];
				}
				$this->_addToParentAttributes($el['attributes']['name'], $data);
				break;

			// Adjust a radio-group => use the children as values
			case 'RadioGroup':
				$el['type'] = $el['name'];
				unset($el['name']);

				if (!isset($el['attributes']['id'])) {
				    $el['attributes']['id'] = $this->_getNextId($el['attributes']['name']);
				}
				
				if( isset( $el['children'] ) ) {
					if( is_array( $el['children'] ) ) {
						$el['attributes']['values']		=	$el['children'];
						unset($el['children']);
					}
				}

				$renderer = null;
				
				// for the radio group, we use the string renderer as renderer, but 
				// only if a template is available at all - if there is none, we 
				// simply let the radiogroup render itself the default way.
				$template = trim( $data );
				if( !empty( $template ) ) {
					$el['renderer'] =& patForms::createRenderer('String');
					$el['renderer']->setTemplate( $data );
					$el['renderer']->setPlaceholder('{PATFORMS_ELEMENT_'.strtoupper( $el['attributes']['id'] ).'_%s}');
				}
				
				if (count($this->_elStack) > 1) {
					$this->_addToParentTag($el, $el['attributes']['name'], true);
				} else {
					$this->addElementDefinitionByArray( $el );
				}
				$this->_characterData( $this->_getPlaceholderForElement( $el['attributes']['id'] ) );
				break;

			// Adjust an enum => use the children as values
			case 'Enum':
			case 'Set':
				$el['type'] = $el['name'];
				unset($el['name']);

				if (!isset($el['attributes']['id'])) {
				    $el['attributes']['id'] = $this->_getNextId($el['attributes']['name']);
				}
				
				if( isset( $el['children'] ) ) {
					if( is_array( $el['children'] ) ) {
						$el['attributes']['values']	=	$el['children'];
						unset($el['children']);
					}
				}
				
				if (count($this->_elStack) > 1) {
					$this->_addToParentTag($el, $el['attributes']['name'], true);
				} else {
					$this->addElementDefinitionByArray( $el );
				}

				$this->_characterData( $this->_getPlaceholderForElement( $el['attributes']['id'] ) );
				break;

			// No reserved value, treat it as an 
			// element and add it to the definitions
			default:
				$el['type'] = $el['name'];
				unset($el['name']);
				
				if (!isset($el['attributes']['id'])) {
				    $el['attributes']['id'] = $this->_getNextId($el['attributes']['name']);
				}

				// add a renderer to the Group
				if (strtolower($el['type']) === 'group') {
					$el['renderer'] = patForms::createRenderer('String');
					$el['renderer']->setTemplate($data);
					$el['renderer']->setPlaceholder('{'.$this->_placeholder.'}', 'name');
				}

				if (count($this->_elStack) > 1) {
					$this->_addToParentTag($el, $el['attributes']['name'], true);
				} else {
					$this->addElementDefinitionByArray( $el );
				}
				$this->_characterData( $this->_getPlaceholderForElement( $el["attributes"]["id"] ) );
				break;
		}
	}

   /**
	* call a method in a namespace handler
	*
	* @access	private
	* @param	string	namespace
	* @param	string	tag name, will be used as method name
	* @param	array	attributes of the tag
	* @param	mixed	content of the tag
	*/
	function &_callNamespaceHandler($ns, $name, $attributes, $data)
	{
		if (!method_exists($this->_namespaceHandlers[$ns], $name)) {
			patErrorManager::raiseError(PATFORMS_PARSER_ERROR_UNKNOWN_TAG, "Unknown tag $name in namespace $ns.");
		}
		$result = &$this->_namespaceHandlers[$ns]->$name( $attributes, $data );
		return $result;
	}
	
   /**
	* add an element to the element definition list
	*
	* The element definition list is used to build the form
	* and also serialized for caching
	*
	* @access	public
	* @param	string	name
	* @param	string	type
	* @param	array	attributes
	* @param	object	renderer
	*/
	function addElementDefinition($name, $type, $attributes, $renderer = null)
	{
		if (!isset($this->_elementDefinitions[$this->_currentForm])) {
			$this->_elementDefinitions[$this->_currentForm] = array();
		}
		$this->_elementDefinitions[$this->_currentForm][] = array(
		                                                'name'          => $name,
														'type'			=> $type,
														'attributes'	=> $attributes,
														'renderer'		=> &$renderer
										 			);
        return true;
	}
	
   /**
	* add an element to the element definition list
	*
	* The element definition list is used to build the form
	* and also serialized for caching
	*
	* @access	public
	* @param	string	name
	* @param	string	type
	* @param	array	attributes
	* @param	object	renderer
	*/
	function addElementDefinitionByArray( $def )
	{
		if (!isset($this->_elementDefinitions[$this->_currentForm])) {
			$this->_elementDefinitions[$this->_currentForm] = array();
		}
		$def['name'] = $def['attributes']['name'];
		$this->_elementDefinitions[$this->_currentForm][] = $def;
		return	true;
	}
	
   /**
	* cdata handler
	*
	* @access	private
	* @param	string		data
	*/
	function _characterData($data)
	{
		if ($this->_depth == 0) {
			$this->_html .= $data;
			return true;
		}
		$this->_data[$this->_depth] .= $data;
	}

   /**
	* get the character data of the element
	*
	* @access	private
	* @return	string
	*/
	function _getCData()
	{
		if ($this->_depth == 0) {
			return	'';
		}
		return $this->_data[$this->_depth];
	}

   /**
    * adds an attribute to the parent tag
	*
	* @access	private
	* @param	string	attribute name
	* @param	mixed	attribute value
	*/
	function _addToParentAttributes( $name, $value )
	{
		$parent	= array_pop( $this->_elStack );
		$parent["attributes"][$name] = $value;
		array_push( $this->_elStack, $parent );

		return true;
	}
	
   /**
	* add child element to parant tag
	*
	* This is used to build enum lists or groups.
	* In the definition of the 
	*
	* @access	private
	* @param	mixed	child to add, normally is an array
	* @param	string	key of the child
	* @param	boolean	defines whether the element always has more the one child.
	*					If set to true the first element will be stored in an array
	* @return	boolean	success, currently always true
	*/
	function _addToParentTag( $child, $key = null, $hasMultiple = false )
	{
		// get the parent tag
		$parent	=	array_pop( $this->_elStack );
		
		// check if there already are children
		if( !$parent["hasChildren"] )
		{
			$parent["hasChildren"]	=	true;
			
			/**
			 * no key defined => just set this as only child
			 */
			if( $key == null && !$hasMultiple )
			{
				$parent["children"]	=	$child;
				
				array_push( $this->_elStack, $parent );
				return	true;
			}
			else
			{
				$parent["children"]	=	array();
			}
		}

		// if a key has been supplied
		if( $key != null )
			$parent["children"][$key]	=	$child;
		else
			array_push( $parent["children"], $child );

		array_push( $this->_elStack, $parent );
		
		return	true;
	}

   /**
    * get the name of the parent tag
	*
	* @access	private
	* @return	string	tag name
	*/
	function _getParentName()
	{
		$parent	=	array_pop( $this->_elStack );
		array_push( $this->_elStack, $parent );

		return $parent['name'];
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
			case "upper":
				$element	=	strtoupper( $element );
				break;
			case "lower":
				$element	=	strtolower( $element );
				break;
			default:
				break;
		}
		
		return	sprintf( $this->{'_'.$template}, $element );
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
		return	sprintf( $this->$template, $form );
	}

   /**
	* get the one form element
	*
	* @access	public
	* @param	string	element name
	* @param 	string	form name, of the parser extracted more than one form
	* @return	object	patForms element
	* @deprecated	Please use getForm() instead and fetch the elements from the form
	*/
	function &getFormElement($name, $form = null)
	{
		$form = &$this->getForm($form);
		if (patErrorManager::isError($form)) {
			return $form;
		}
		return $form->getElementByName($name);
	}

   /**
	* get the complete form object
	*
	* @access	public
	* @return	object	patForms object
	*/
	function &getForm($name = null)
	{
		if ($name === null && count($this->_elementDefinitions) === 1) {
			reset($this->_elementDefinitions);
			$name = key($this->_elementDefinitions);
		}
		
		if ($name === null) {
			if (!is_object($this->_form)) {
				require_once PATFORMS_INCLUDE_PATH . '/Collection.php';
				$this->_form = new patForms_Collection();
			}
			foreach ($this->_elementDefinitions as $formName => $elementDefinitions) {
				if ($this->_form->containsForm($formName)) {
					continue;					
				}
				$this->_form->addForm($this->_createForm($formName));
			}
			return $this->_form;
		}

		if (is_object($this->_form[$name])) {
			return	$this->_form[$name];
		}
		
		if (!isset($this->_elementDefinitions[$name])) {
			return patErrorManager::raiseError(PATFORMS_PARSER_ERROR_FORM_NOT_FOUND, 'The form does not exist.');
		}

		$this->_form[$name] = $this->_createForm($name);
		return $this->_form[$name];
	}

   /**
    * create a new form from a form definition
    *
    * @access protected
    * @param  string        name of the form
    * @return patForms
    */
	function &_createForm($name)
	{
	    // prepare the attributes
	    if (!isset($this->_formAttributes[$name])) {
			$this->_formAttributes[$name] = array(
													'name' => 'form'
												);
		}

		// check for a namespace
	    $namespace = false;
	    if (isset($this->_formAttributes[$name]['namespace'])) {
	    	$namespace = $this->_formAttributes[$name]['namespace'];
	    	unset($this->_formAttributes[$name]['namespace']);
	    }

	    // check for defined listeners
	    $listeners = array();
	    foreach ($this->_formAttributes[$name] as $attName => $attValue) {
	    	if (strpos($attName, 'on') !== 0) {
	    	    continue;
	    	}
	    	unset($this->_formAttributes[$name][$attName]);
	    	$attName[2] = strtoupper($attName[2]);
	    	$listeners[$attName] = $attValue;
	    }
	     
	    $form = &patForms::createForm($this->_elementDefinitions[$name], $this->_formAttributes[$name]);
	    if ($namespace != null) {
	    	$form->setNamespace($namespace);
	    }
	    
	    if (!empty($listeners)) {
	    	foreach ($listeners as $event => $handler) {
	    		$form->registerEventHandler($event, $handler);
	    	}
	    }

	    return $form;
	}
	
   /**
	* get the HTML code where all form elements have been replaced with variables
	*
	* @access	public
	* @return	string	HTML code
	*/
	function getHTML()
	{
		if( $this->_html != null )
			return	$this->_html;
			
		if( $this->_outputFile == null )
			return	false;

		$this->_html = file_get_contents( $this->_adjustFilename( $this->_outputFile ) );
		if( $this->_html === false )
		{
			return patErrorManager::raiseError(
				PATFORMS_PARSER_ERROR_FILE_NOT_FOUND, 
				"Sourcefile could not be read", 
				"Sourcefile: " . $this->_sourceFile 
			);
		}

		return	$this->_html;
	}

   /**
	* create a new parser
	*
	* Use this method to create a specialty
	* parser, that uses a driver.
	*
	* This could include a parser that is a renderer
	* at the same time.
	*
	* @static
	* @access	public
	* @param	mixed	driver name
	* @return	object patForms_Parser
	*/
	function &createParser( $driver = null )
	{
		// not based on any driver
		if( $driver == null )
		{
			$parser	=	new patForms_Parser;
		}
		else
		{
			$driverFile	=	dirname( __FILE__ ) . "/Parser/{$driver}.php"; 
			if( !@include_once( $driverFile ) )
			{
				return patErrorManager::raiseError(
					PATFORMS_PARSER_ERROR_DRIVER_FILE_NOT_FOUND,
					"Driver file '$driverFile' could not be loaded, file not found"
				);
			}

			$parserClass	=	"patForms_Parser_{$driver}";
			if( !class_exists( $parserClass ) )
			{
				return patErrorManager::raiseError(
					PATFORMS_PARSER_ERROR_DRIVER_CLASS_NOT_FOUND,
					"Driver file has been loaded correctly, but the according driver class '$parserClass' could not be found"
				);
			}
			$parser		=	new $parserClass;
		}
		
		return	$parser;
	}
	
   /**
    * Create a form from an FTMPL (Form Template) file.
	*
	* This method will return a form object, that already
	* contains a reference to the parser, that can be used
	* as a renderer for the form.
	*
	* @access	public
	* @static
	* @param	string		driver for the parser, use null for the default parser
	* @param	string		form template to create the form
	* @param	string		outputfile for the resulting HTML code without form elements
	* @return	object		patForms object
	*/
	function &createFormFromTemplate( $driver, $formTemplate, $outputFile = null )
	{
		// create a new parser
		$parser		=&	patForms_Parser::createParser( $driver );
		$success	=&	$parser->parseFile( $formTemplate, $outputFile );
		if (patErrorManager::isError($success)) {
			return $success;
		}
	
		$form =& $parser->getForm();
		$form->setRenderer( $parser, array( 'includeElements' => true ) );
		
		return $form;
	}

   /**
	* get the namespace of patForms elements from the
	* html page.
	*
	* To make this method work, you will need an
	* xmlns:foo="http://www.php-tools.net/patForms/basic"
	* attribute in any tag of your page.
	*
	* @access	public
	* @param	string	$html		HTML document that should be parsed
	* @return	string				namespace or a patError
	*/
	function getNamespacePrefix( $html )
	{
		$regExp = '~xmlns:([^=]+)=["\']http://www.php-tools.net/patForms/basic["\']~im';
		
		$matches = array();
		$result = preg_match($regExp, $html, $matches);

		if ($result) {
			return	$matches[1];		
		}
		return patErrorManager::raiseError(
			PATFORMS_PARSER_ERROR_NO_NAMESPACE, 
			"No namespace for patForms declared." 
		);
	}

   /**
	* Set a static property.
	*
	* Static properties are stored in an array in a global variable,
	* until PHP5 is ready to use.
	*
	* @static
	* @param	string	property name
	* @param	string	property value
	* @see		getStaticProperty()
	*/
	function setStaticProperty($property, &$value)
	{
		$GLOBALS["_patForms_Parser"][$property]	= &$value;
	}

   /**
	* Get a static property.
	*
	* Static properties are stored in an array in a global variable,
	* until PHP5 is ready to use.
	*
	* @static
	* @param	string	property name
	* @return	string	property value
	* @see		setStaticProperty()
	*/
	function &getStaticProperty($property)
	{
		if (isset($GLOBALS["_patForms_Parser"][$property])) {
			return $GLOBALS["_patForms_Parser"][$property];
		}
		return patErrorManager::raiseWarning(
			PATFORMS_PARSER_ERROR_NO_STATIC_PROPERTY, 
			"Static property '$property' does not exist." 
		);
	}

   /**
    * Get the id for an element
    *
    * @access protected
    * @param  string    element name
    * @return string
    */
	function _getNextId($elementNyme)
	{
	    return $elementNyme;
	}
	
}
?>