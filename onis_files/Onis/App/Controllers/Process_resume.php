<?php

namespace Onis\App\Controllers;

/**
 * Class Process_resume
 *
 * Produce an aggregate total of the column tiv_2012 by county and line.
 */
class Process_resume extends Process_resume_error
{
	static $objInterface;

	/**
     * Get instance to implements singleton.
     * 
     * @method getInstance
     * @param string $strLang Language
     * @return Onis\App\Controllers\Process_resume Process_resume::$objInterface Class instance
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	static function getInstance(string $strLang = '')
	{
		if(empty(Process_resume::$objInterface)) Process_resume::$objInterface = new Process_resume($strLang);

		return Process_resume::$objInterface;
	}

	/**
     * Class constructor
     * 
     * @method __construct
     * @param string $strLang Language
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	public function __construct(string $strLang)
	{
		if($strLang != '') $this->strLang = $strLang;

		parent::__construct();
	}

	/**
     * Get csv headers
     * 
     * @method getHeaders
     * @param string $strHeaderLine Header line
     * @return array $arrHeader Header in array by numeric index
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	public function getHeaders(string $strHeaderLine):array
	{
		$arrHeaderItem = explode(',', $strHeaderLine);
		$arrHeader = [];

		foreach($arrHeaderItem as &$strHeader) $arrHeader[] = $strHeader;

		return $arrHeader;
	}

	/**
     * Parse csv data file
     * 
     * @method doParse
     * @param string $strFullFilePath Full file path
     * @return array $arrRs File parsed data
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	public function doParse(string $strFullFilePath):array
	{
		$arrRs = [];

		if(!file_exists($strFullFilePath)) throw new \Exception('Code: F001 | '. $this->getErrorCode('F001'));

		$strFile = file_get_contents($strFullFilePath);

		$strR = (strpos($strFile, "\n") !== false)?"\n":"\r";

		$arrLines = explode($strR, $strFile);

		$arrHeaders = $this->getHeaders(array_shift($arrLines));

		foreach($arrLines as &$strLine)
		{
			$strLine = trim($strLine);

			$arrLine = explode(',', $strLine);

			$arrRsLine = [];

			$intIdx = 0;

			foreach($arrLine as &$strLineItem)
			{
				if(!isset($arrHeaders[$intIdx])) continue;

				$arrRsLine[$arrHeaders[$intIdx]] = $strLineItem;

				$intIdx++;
			}

			$arrRs[] = $arrRsLine;
		}

		return $arrRs;
	}

	/**
     * Get and process data for challenge
     * 
     * @method getDataForChallenge
     * @param array $arrGroupBy To know how will grouping
     * @param array $arrFields In order to know wich fields we want to process
     * @param array $arrDataFile Array with data from file
     * @param string $strProcessType Process type to know if we going to write file or not
     * @param string $strOutputFolder Output folder
     * @param string $strOutputFileName Output file name
     * @return string $jsonData Processed data in JSON format
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	public function getDataForChallenge(array $arrGroupBy, array $arrFields, array $arrDataFile, string $strProcessType, string $strOutputFolder, string $strOutputFileName):string
	{
		$arrJson = [];

		if(!is_dir($strOutputFolder)) throw new \Exception('Code: F002 | '. $this->getErrorCode('F002'));

		foreach($arrGroupBy as &$strGroup) $arrJson[$strGroup] = [];

		foreach($arrDataFile as &$arrLine)
		{
			foreach($arrGroupBy as &$strGroup)
			{
				if(!array_key_exists($strGroup, $arrLine)) continue;

				$strGroupValue = trim($arrLine[$strGroup]);

				if(!array_key_exists($strGroupValue, $arrJson[$strGroup]))
				{
					$arrJson[$strGroup][$strGroupValue] = [];

					foreach($arrFields as &$strField) $arrJson[$strGroup][$strGroupValue][$strField] = 0;
				}
				
				foreach($arrFields as &$strField)
				{
					if(!array_key_exists($strField, $arrLine)) continue;
					
					if(is_numeric($arrLine[$strField]))
					{
						$arrJson[$strGroup][$strGroupValue][$strField] += (float)$arrLine[$strField];

						$arrJson[$strGroup][$strGroupValue][$strField] = round($arrJson[$strGroup][$strGroupValue][$strField], 2);
					}
				}
			}
		}

		$jsonData = json_encode($arrJson);

		if($strProcessType == 'cli')
		{
			$strOutputFileName = strtolower($strOutputFileName);

			if(strpos($strOutputFileName, '.json') === false) $strOutputFileName .= '.json';

			if(!file_put_contents($strOutputFolder . $strOutputFileName, $jsonData))
				throw new \Exception('Code: F003 | '. $this->getErrorCode('F003'));
		}

		return $jsonData;
	}
}