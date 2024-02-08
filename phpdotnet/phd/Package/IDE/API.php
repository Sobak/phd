<?php
/**
 * Class to load and parse the files generated by the PhD IDE Package.
 *
 * PHP version 5.3+
 *
 * @category PhD
 * @package  PhD_IDE
 * @author   Moacir de Oliveira <moacir@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD Style
 * @link     https://doc.php.net/phd/
 */
namespace phpdotnet\phd;

/**
 * Class to load and parse the files generated by the PhD IDE Package.
 * Make sure that the ide-xml/ dir and the ide-funclist.txt file
 * exist in the PhD output directory given in the __construct.
 *
 * To generate those files run:
 * $ phd --docbook .manual.xml --package IDE --output <your output dir>
 *
 * Use the value passed with --output to create this class.
 *
 * @category PhD
 * @package  PhD_IDE
 * @author   Moacir de Oliveira <moacir@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD Style
 * @link     https://doc.php.net/phd/
 */
class Package_IDE_API
{
    /**
     * Output directory of the functions format in the IDE Package.
     *
     * @var string
     */
    const FUNCTIONS_DIR = 'ide-xml';

    /**
     * Output file of the funclist format in the IDE Package.
     *
     * @var string
     */
    const FUNCLIST_FILE = 'ide-funclist.txt';

    /**
     * PhD output directory.
     *
     * @var string
     */
    private $dir;

    /**
     * Array with the functions of the file ide-funclist.txt.
     *
     * @var array
     */
    private $funclist;

    /**
     * Creates a new instace of Package_IDE_API.
     *
     * @param string $dir PhD output directory.
     */
    public function __construct($dir)
    {
        if (file_exists($dir)) {
            if (is_dir($dir)) {
                $this->dir = substr($dir, -1) === DIRECTORY_SEPARATOR
                        ? $dir
                        : $dir . DIRECTORY_SEPARATOR;
                $this->funclist = file($this->dir . self::FUNCLIST_FILE, FILE_IGNORE_NEW_LINES);
            } else {
                trigger_error('Is the PhD output directory a file?', E_USER_ERROR);
            }
        } else {
            trigger_error('The PhD output directory does not exist.', E_USER_ERROR);
        }
    }

    /**
     * Loads a function file and returns a Package_IDE_API_Function.
     *
     * @param string $filename File of the function.
     * @return Package_IDE_API_Function Object representing the function.
     */
    private function loadFunction($filename)
    {
        if (!file_exists($filename)) {
            return NULL;
        }
        $xml = simplexml_load_file($filename);
        return new Package_IDE_API_Function($xml);
    }

    /**
     * Converts a function name to the correct .xml file.
     *
     * @param string $function Function name.
     * @return string File name.
     */
    private function functionToFilename($function)
    {
        return str_replace(
            array('->', '::', '()'),
            array('.', '.', ''), $function) . '.xml';
    }

    /**
     * Returns an array with the functions in the ide-funclist.txt file.
     *
     * @return array Functions list.
     */
    public function getFunctionList()
    {
        return $this->funclist;
    }

    /**
     * Returns the function object of the name given.
     *
     * @param string $functionName Function name.
     * @param string $class TODO.
     * @return Package_IDE_API_Function A function object.
     */
    public function getFunctionByName($functionName, $class = null)
    {
        return $this->loadFunction($this->dir
            . self::FUNCTIONS_DIR
            . DIRECTORY_SEPARATOR
            . $this->functionToFilename($functionName));
    }

    /**
     * Returns the list of methods of a class.
     *
     * @param string $className Name of the class.
     * @return array An array of Package_IDE_API_Function.
     */
    public function getMethodsByClass($className)
    {
        $methods = array();
        $class = $className . '.';
        foreach ($this->funclist as $function) {
            if (strcmp($class, substr($function, 0, strlen($class))) === 0) {
                $methods[] = $this->getFunctionByName(trim($function));
            }
        }
        return $methods;
    }
}
