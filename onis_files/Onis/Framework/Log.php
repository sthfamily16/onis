<?php

namespace Onis\Framework;

/**
 * Class Log
 *
 * To make a log
 */
class Log
{
	static $strSavePath = '';
	static $strFileName = '';
	static $strNl = "\n";
	static $bolWrLive = false;
	static $strLog = '';
	static $bolRecreateLog = true;

	/**
     * Set log line
     * 
     * @method setLn
     * @param string $str Some text
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	static function setLn(string $str)
	{
		$str = date('Y-m-d H:i:s') .' '. $str . self::$strNl;

		self::$strLog .= $str;

		if(self::$bolWrLive)
		{
			if(empty(self::$strSavePath)) self::$strSavePath = '.'. DIRECTORY_SEPARATOR;
			if(empty(self::$strFileName)) self::$strFileName = 'log'. date('Ymd') .'.log';

			file_put_contents(self::$strSavePath . self::$strFileName, $str, FILE_APPEND);
		}

		echo $str;
	}

	/**
     * Print visual separator
     * 
     * @method setSeparator
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	static function setSeparator()
	{
		$str = self::$strNl .':::::::::::::::::::::::::::'. self::$strNl;

		self::$strLog .= $str;

		if(self::$bolWrLive)
		{
			if(empty(self::$strSavePath)) self::$strSavePath = '.'. DIRECTORY_SEPARATOR;
			if(empty(self::$strFileName)) self::$strFileName = 'log'. date('Ymd') .'.log';

			file_put_contents(self::$strSavePath . self::$strFileName, $str, FILE_APPEND);
		}

		echo $str;
	}

	/**
     * Save log file
     * 
     * @method doSave
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	static function doSave()
	{
		if(empty(self::$strSavePath)) self::$strSavePath = '.'. DIRECTORY_SEPARATOR;
		if(empty(self::$strFileName)) self::$strFileName = 'log'. date('Ymd') .'.log';

		if(self::$bolRecreateLog) file_put_contents(self::$strSavePath . self::$strFileName, self::$strLog);
		else file_put_contents(self::$strSavePath . self::$strFileName, self::$strLog, FILE_APPEND);
	}
}