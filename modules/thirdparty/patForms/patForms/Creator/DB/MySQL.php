<?php
/**
 * patForms Creator DB_MySQL
 *
 * This is an experiment and should not be used in
 * production. The Creator classes will be replaced
 * by the Definition classes in v1.1.0.
 *
 * $Id: MySQL.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @package		patForms
 * @subpackage	Creator
 * @deprecated 
 */

/**
 * PEAR::DB class
 */
 require_once 'DB.php';
 
/**
 * Error: could not connect to the database
 */
 define( 'PATFORMS_CREATOR_DB_MYSQL_ERROR_NO_CONNECTION', 'patForms:Creator:DB:01' );

/**
 * patForms Creator DB_MySQL
 *
 * This is an experiment and should not be used in
 * production. The Creator classes will be replaced
 * by the Definition classes in v1.1.0.
 *
 * $Id: MySQL.php,v 1.1 2006/04/03 20:41:04 eric Exp $
 *
 * @deprecated 
 * @package		patForms
 * @subpackage	Creator
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @license		LGPL, see license.txt for details
 * @link		http://www.php-tools.net
 */
class patForms_Creator_DB_MySQL extends patForms_Creator
{
   /**
	* create a form for a database
	*
	* @access	public
	* @param	mixed		either a dsn or a DB object
	* @param	mixed		either a table name or a result
	* @return	object patForms
	*/
	function &create( $db, $source, $options = array() )
	{
		if( is_string( $db ) )
		{
			$db	=	DB::connect( $db );
			
			if( DB::isError( $db ) )
			{
				return patErrorManager::raiseError(
					PATFORMS_CREATOR_DB_MYSQL_ERROR_NO_CONNECTION,
					'Could not connect to the specified database.',
					$db->getMessage()." >> ".$db->getUserInfo()
				);
			}
		}

		$form	=	&patForms::createForm( null, array( 'name' => 'patForms_Creator_DB_Mysql_Form' ) );
		
		$info	=	$db->getAll( 'DESCRIBE '.$source, array(), DB_FETCHMODE_ASSOC );
		
		$cntElements	=	count( $info );
		for( $i = 0; $i < $cntElements; $i++ )
		{
			$attribs	=	array(
									'edit'	=>	'yes',
									'title'	=>	$info[$i]['Field'],
									'label'	=>	$info[$i]['Field'],
									'name'	=>	$info[$i]['Field']
								);

			if( isset( $info[$i]['Default'] ) )
			{
				$attribs['default']	=	$info[$i]['Default'];
			}
								
			$type		=	$this->parseType( $info[$i]['Type'] );
			if( !is_array( $type ) )
			{
				$type	=	array(
									'type'			=>	'String',
									'attributes'	=>	array()
								);
			}

			$attribs	=	array_merge( $attribs, $type['attributes'] );
		
			$form->addElement( $form->createElement( $info[$i]['Field'], $type['type'], $attribs ) );
		}
		
		return	$form;
	}

	function parseType( $type )
	{
		/**
		 * check for integer
		 */
		$matches	=	array();
		if( preg_match( '/int\(([0-9]+)\)/', $type, $matches ) )
		{
			$result	=	array(
								'type'			=>	'Number',
								'attributes'	=>	array(
															'numberformat'	=>	'0||'
														)
							);
			return $result;
		}

		/**
		 * float
		 */
		if( preg_match( '/float\(([0-9,]+)\)/', $type, $matches ) )
		{
			$result	=	array(
								'type'	=>	'Number'
							);
			return $result;
		}
	
		/**
		 * varchar
		 */
		if( preg_match( '/varchar\(([0-9]+)\)/', $type, $matches ) )
		{
			$result	=	array(
								'type'			=>	'String',
								'attributes'	=>	array(
															'maxlength'	=>	$matches[1]
														)
							);
			return $result;
		}

		/**
		 * enum
		 */
		if( preg_match( '/enum\((.+)\)/', $type, $matches ) )
		{
			$tmp	=	explode( ',', $matches[1] );
			$values	=	array();
			foreach( $tmp as $value )
			{
				$value	=	substr( $value, 1, -1 );
				array_push( $values, array( 'label' => $value, 'value' => $value ) );
			}

			$result	=	array(
								'type'			=>	'Enum',
								'attributes'	=>	array(
															'values'	=>	$values
														)
							);


			return $result;
		}

		/**
		 * set
		 */
		if( preg_match( '/set\((.+)\)/', $type, $matches ) )
		{
			$tmp	=	explode( ',', $matches[1] );
			$values	=	array();
			foreach( $tmp as $value )
			{
				$value	=	substr( $value, 1, -1 );
				array_push( $values, array( 'label' => $value, 'value' => $value ) );
			}

			$result	=	array(
								'type'			=>	'Set',
								'attributes'	=>	array(
															'values'	=>	$values
														)
							);


			return $result;
		}

		/**
		 * Datetime
		 */
		if( preg_match( '/^datetime$/', $type, $matches ) )
		{
			$result	=	array(
								'type'			=>	'Date',
								'attributes'	=>	array(
															'dateformat'	=>	'%Y-%m-%d %H:%M:%S'
														)
							);


			return $result;
		}

		/**
		 * time
		 */
		if( preg_match( '/^time$/', $type, $matches ) )
		{
			$result	=	array(
								'type'			=>	'Date',
								'attributes'	=>	array(
															'dateformat'	=>	'%H:%M:%S'
														)
							);


			return $result;
		}
	}
}
?>