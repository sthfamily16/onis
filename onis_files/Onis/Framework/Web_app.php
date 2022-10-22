<?php

namespace Onis\Framework;

use Onis\Framework\View;

/**
 * Class Web_app
 *
 * Serve a web app
 */
class Web_app
{
	static $objInterface;
	private $arrConf;

	/**
     * Get instance to implements singleton.
     * 
     * @method getInstance
     * @param string $strAppPath App root folder
     * @return Onis\Framework\Web_app Web_app::$objInterface Class instance
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	static function getInstance(string $strAppPath = '')
	{
		if(empty(Web_app::$objInterface)) Web_app::$objInterface = new Web_app($strAppPath);

		return Web_app::$objInterface;
	}

	/**
     * Constructor
     * 
     * @method __construct
     * @param string $strAppPath App root folder
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	public function __construct(string $strAppPath)
	{
		$strConfFile = $strAppPath . 'config.conf';

		if(file_exists($strConfFile))
		{
			$strFile = file_get_contents($strConfFile);

			$arrLines = explode("\n", $strFile);

			foreach($arrLines as &$strLine)
			{
				$strLine = trim($strLine);

				if(empty($strLine) || substr($strLine, 0, 1) == '#') continue;

				$arrPair = explode('=', $strLine);

				$strIdx = $arrPair[0];
				
				$this->arrConf[$strIdx] = str_replace($strIdx.'=', '', $strLine);
			}
		}
		else throw new \Exception('File isnt exists: '. $strConfigFilePath);

		View::$strViewPath = $strAppPath .'app'. DIRECTORY_SEPARATOR .'views'. DIRECTORY_SEPARATOR;
	}

	/**
     * Get app configuration values
     * 
     * @method getConfig
     * @param string $strIdx Configuration key
     * @return string $strConfigVal Configuration value
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	public function getConfig(string $strIdx):string
	{
		$strConfigVal = $this->arrConf[$strIdx];

		return $strConfigVal;
	}

	/**
     * Process web request, mapping route
     * 
     * @method processRequest
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	public function processRequest()
	{
		$strController = $this->arrConf['default_controller'];
		$strMethod = $this->arrConf['default_method'];

		if($_SERVER['REQUEST_URI'] != '/')
		{
			if(preg_match_all('/\/([a-z0-9\-]+)+?/i', $_SERVER['REQUEST_URI'], $arrMatch))
			{
				$strController = $arrMatch[1][0];
				if(count($arrMatch[1]) > 1) $strMethod = $arrMatch[1][1];
			}
		}
		
		require_once(ROOT_APP .'app'. DIRECTORY_SEPARATOR .'controllers'. DIRECTORY_SEPARATOR . ucfirst(strtolower($strController)) .'.php');

		eval('$objController = new Onis\\Web\\Controller\\'.$strController.'();');

		call_user_func(array($objController, $strMethod));
	}
}