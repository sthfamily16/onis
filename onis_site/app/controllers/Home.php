<?php

namespace Onis\Web\Controller;

use Onis\Framework\View;
use Onis\Framework\Web_app;

/**
 * Class Home
 *
 * To solve challenge via web
 */
class Home
{
	/**
     * Show the base template to work
     * 
     * @method index
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	public function index()
	{
		View::show('generals.header', ['strTitle' => 'Onis']);
		View::show('home.index');
		View::show('generals.footer', ['arrjs' => ['/js/home.js']]);
	}

	/**
     * Process file with CLI in order to reuse a previous develpment
     * 
     * @method process
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
	public function process()
	{
		$arrRs = ['error' => '', 'data' => ''];

		if($_FILES['file']['error']) $arrRs['error'] = 'Upload error';
		else
		{
			$strFileName = 'onis'. date('YmdHis'). '.csv';

			if(!move_uploaded_file($_FILES['file']['tmp_name'], TMP_DIR . $strFileName)) $arrRs['error'] = 'Cant move uploaded file';
			else
			{
				$strFile = TMP_DIR . $strFileName;

				$strProcessFile = Web_app::getInstance()->getConfig('parse_file_onis_process');
		
				$strProcessFileGrouping = Web_app::getInstance()->getConfig('data_grouping');
				
				$strProcessFileValues = Web_app::getInstance()->getConfig('data_values');

				$strProcess = basename($strProcessFile);
				
				$strDir = dirname($strProcessFile);

				$strCmd = 'cd '. $strDir .' && php '. $strProcess .' "web" "en" "'. $strFile .'" "'. $strProcessFileGrouping .'" "'. $strProcessFileValues .'"';

				exec($strCmd, $arrRs);

				$arrRs['data'] = json_decode($arrRs[0]);
			}
		}

		echo json_encode($arrRs);
	}
}