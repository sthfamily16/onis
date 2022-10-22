<?php

namespace Onis\App\Controllers;

use Onis\Framework\Parse_config;
/**
 * Class Process_resume_error
 *
 * Base to handle errors in process_resume class
 */
class Process_resume_error
{
	protected $strLang;
	private $arrErrors;
	
	/**
     * Construct
     * 
     * @method __construct
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	public function __construct()
	{
		$strLangFile = strtolower(str_replace('_error', '.msg', basename(__CLASS__)));

		$strLangFilePath = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . '..'). DIRECTORY_SEPARATOR .'Lang'. DIRECTORY_SEPARATOR . $this->strLang . DIRECTORY_SEPARATOR . $strLangFile;

		$this->arrErrors = Parse_config::getInstance()->doParse($strLangFilePath);
	}

	/**
     * Get error description in selected languaje}
     * 
     * @method getErrorCode
     * @param string $strCode Error code
     * @return string $strDescription Error description
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	protected function getErrorCode(string $strCode):string
	{
		$strDescription = $this->arrErrors[$strCode];

		return $strDescription;
	}
}