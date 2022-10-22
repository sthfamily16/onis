<?php

use Onis\App\Controllers\Process_resume;
use Onis\Framework\Parse_config;
use Onis\Framework\Log;

require_once(realpath('.'. DIRECTORY_SEPARATOR . '..'). DIRECTORY_SEPARATOR .'onis_files'. DIRECTORY_SEPARATOR . 'autoloader.php');

Log::$strSavePath = realpath('.'. DIRECTORY_SEPARATOR . 'logs'). DIRECTORY_SEPARATOR;

$arrError = [];

if(count($argv) < 6) $arrError[] = 'The process uses at least 6 params';
else
{
	foreach($argv as &$strVal) $strVal = trim($strVal);

	if($argv[1] != 'cli' && $argv[1] != 'web') $arrError[] = 'Invalid process type';

	if(strlen($argv[2]) != 2) $arrError[] = 'Invalid language';

	if($argv[3] == '') $arrError[] = 'Invalid file';

	if(!preg_match('/\s*\.csv/i', $argv[3])) $arrError[] = 'We just can process csv file';

	if($argv[4] == '') $arrError[] = 'Invalid grouping';

	if($argv[5] == '') $arrError[] = 'Invalid fields';

	if($argv[1] == 'cli' && isset($argv[6]) && $argv[6] == '') $arrError[] = 'Invalid output file name';
	else if($argv[1] == 'web') $argv[6] = '';
}

$arrConf = Parse_config::getInstance()->doParse('.'. DIRECTORY_SEPARATOR .'main.conf');

if($argv[1] == 'cli')
{
	Log::setSeparator();
	Log::setLn('Start');
}

if(count($arrError) > 0)
{
	foreach($arrError as &$strError)
	{
		if($argv[1] == 'cli') Log::setLn('ERROR: '. $strError);	
	} 

	if($argv[1] == 'cli') Log::setLn('Finish');
}

$strJson = '{}';

try
{
	$objProcessResume = Process_resume::getInstance($argv[2]);

	$arrFile = $objProcessResume->doParse($argv[3]);

	$arrGroupBy = explode(',', $argv[4]);

	$arrFields2Process = explode(',', $argv[5]);

	$strJson = $objProcessResume->getDataForChallenge($arrGroupBy, $arrFields2Process, $arrFile, $argv[1], $arrConf['output_folder'], $argv[6]);

	if($argv[1] == 'cli')
	{
		if($strJson == '[]')
		{
			 Log::setLn('Not records found');

			 $strJson = '{}';
		}
		else Log::setLn('Success');
	}
}
catch(Exception $e)
{ 
	if($argv[1] == 'cli') Log::setLn('ERROR: '. $e->getMessage());
}

if($argv[1] == 'cli')
{
	Log::setLn('Finish');

	Log::doSave();
}
else echo $strJson;