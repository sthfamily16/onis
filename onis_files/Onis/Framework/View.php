<?php

namespace Onis\Framework;

/**
 * Class View
 *
 * Serve views
 */
class View
{
	static $strViewPath;

    /**
     * Display view
     * 
     * @method show
     * @param string $strView File view
     * @param array $arrData Data should be parsed
     *
     * @author Hugo Roldan
     * @since 2022-10-21
     */
    static function show(string $strView, array $arrData = [])
    {
        $strView = str_replace(['/', '.'], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $strView);

        if(strpos($strView, '.php') === false) $strView .= '.php';

        extract($arrData);

        require_once(self::$strViewPath . $strView);
    }
}