<?php

namespace Aoe\Restler;

use TYPO3\CMS\Core\Utility\VersionNumberUtility;

class Bootstrap
{
    public static function isVersion9()
    {
        return VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 9000000;
    }
}