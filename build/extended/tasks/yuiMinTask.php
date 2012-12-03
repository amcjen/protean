<?php
/**
 * Uses the Phing Task
 */
require_once 'phing/Task.php';
 
/**
 * Task to compress files using YUI Compressor.
 *
 * @author      Keith Pope
 */
class yuiMinTask extends Task
{
    /**
     * path to YuiCompressor
     *
     * @var  string
     */
    protected $yuiPath;
    
    /**
     * the source files
     *
     * @var  FileSet
     */
    protected $filesets    = array();
    
    /**
     * Whether the build should fail, if
     * errors occured
     *
     * @var boolean
     */
    protected $failonerror = false;
    
    /**
     * directory to put minified javascript files into
     *
     * @var  string
     */
    protected $targetDir;
 
    /**
     * sets the path where JSmin can be found
     *
     * @param  string  $yuiPath
     */
    public function setYuiPath( $yuiPath )
    {
        $this->yuiPath = $yuiPath;
    }
 
    /**
     *  Nested creator, adds a set of files (nested fileset attribute).
     */
    public function createFileSet()
    {
        $num = array_push( $this->filesets, new FileSet() );
        return $this->filesets[$num - 1];
    }
 
    /**
     * Whether the build should fail, if an error occured.
     *
     * @param boolean $value
     */
    public function setFailonerror( $value )
    {
        $this->failonerror = $value;
    }
 
    /**
     * sets the directory compressor traget dir
     *
     * @param  string  $targetDir
     */
    public function setTargetDir( $targetDir )
    {
        $this->targetDir = $targetDir;
    }
 
    /**
     * The init method: Do init steps.
     */
    public function init()
    {
        return true;
    }
 
    /**
     * The main entry point method.
     */
    public function main()
    {
        $command = 'java -jar {yuipath} {src} -o {target}';
        
        foreach( $this->filesets as $fs ) 
        {
            try 
            {
                $files    = $fs->getDirectoryScanner( $this->project )->getIncludedFiles();
                $fullPath = realpath( $fs->getDir( $this->project ) );
                
                foreach( $files as $file ) 
                {
                    $this->log( 'Minifying file ' . $file );
										
										$fileName = str_replace( $fullPath, '', $file );
										$info = pathinfo($fileName);
										$fileName = basename($fileName,'.'.$info['extension']) . '.min.' . $info['extension'];
	                  $target = $this->targetDir . '/' . $fileName;

                    // if( file_exists( dirname( $target ) ) == false ) 
                    //                {
                    //                    mkdir( dirname( $target ), 0700, true );
                    //                }
                    
                    $cmd = str_replace( '{src}', $fullPath . DIRECTORY_SEPARATOR . $file, $command );
                    $cmd = str_replace( '{target}', $target, $cmd );
                    $cmd = str_replace( '{yuipath}', realpath( $this->yuiPath ), $cmd );
                    
                    $output = array();
                    $return = null;
         
                    exec( $cmd, $output, $return );
        
                    foreach( $output as $line ) 
                    {
                        $this->log( $line, Project::MSG_VERBOSE );
                    }
                    
                    if( $return != 0 )
                    {
                      throw new BuildException( "Task exited with code $return" );
                    }
 
                }
            } 
            
            catch( BuildException $be ) 
            {
                // directory doesn't exist or is not readable
                if ($this->failonerror) 
                {
                    throw $be;
                } 
                else 
                {
                    $this->log($be->getMessage(), $this->quiet ? Project::MSG_VERBOSE : Project::MSG_WARN);
                }
            }
        }
    }
}
?>
