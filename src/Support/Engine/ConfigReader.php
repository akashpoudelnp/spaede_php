<?php

namespace Spaede\Support\Engine;

use Exception;

class ConfigReader
{
    private static array $config = [];

    public static function getConfig($key)
    {
        $configKeyParts = explode('.', $key);

        if (count($configKeyParts) < 2) {
            return null;
        }

        $fileName = $configKeyParts[0];
        unset($configKeyParts[0]);
        $arrayAccess = implode('.', $configKeyParts);

        if (isset(static::$config[$fileName])) {
            return access_array_by_dot($arrayAccess, static::$config[$fileName]);
        }

        $filePath = sprintf(
            "../config/%s.php",
            $fileName
        );

        if (!file_exists($filePath)) {
            throw new Exception('Configuation file not found at: '.$filePath);
        }
        $contents = include($filePath);

        static::$config[$fileName] = $contents;

        return access_array_by_dot($arrayAccess, $contents);
    }

}