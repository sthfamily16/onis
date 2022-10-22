<?php

namespace Onis\Framework;

/**
 * Class Parse_config
 *
 * Parse some configuration file
 */
class Parse_config
{
	static $objInterface;

	/**
     * Get instance to implements singleton.
     * 
     * @method getInstance
     * @param string $strLang Language
     * @return Onis\Framework\Parse_config Parse_config::$objInterface Class instance
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	static function getInstance(string $strLang = '')
	{
		if(empty(Parse_config::$objInterface)) Parse_config::$objInterface = new Parse_config($strLang);

		return Parse_config::$objInterface;
	}

	/**
     * To parse custom config files
     * 
     * @method doParse
     * @param string $strConfigFilePath File path
     * @return array $arrData Data in file already parsed
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	static function doParse(string $strConfigFilePath = ''):array
	{
		$arrData = [];

		if(file_exists($strConfigFilePath))
		{
			$strFile = file_get_contents($strConfigFilePath);

			$arrLines = explode("\n", $strFile);

			foreach($arrLines as &$strLine)
			{
				$strLine = trim($strLine);

				if(empty($strLine) || substr($strLine, 0, 1) == '#') continue;

				$arrPair = explode('=', $strLine);

				$strIdx = $arrPair[0];
				
				$arrData[$strIdx] = str_replace($strIdx.'=', '', $strLine);
			}
		}
		else throw new \Exception('File isnt exists: '. $strConfigFilePath);

		return $arrData;
	}	
}