<?php

namespace Spaede\Support\Engine;

use RuntimeException;

class EnvParser
{
    private static array $config = [];

    public static function getConfig($key = null)
    {
        if (static::$config) {
            return static::$config;
        }

        static::$config = static::getValuesFromFile();

        if ($key) {
            return static::$config[$key] ?? null;
        }

        return static::$config;
    }

    private static function getValuesFromFile(): array
    {
        if (!file_exists('../.env')) {
            throw new RuntimeException('Missing environment file');
        }

        $config = [];

        // We get contents
        $contents = explode(PHP_EOL, file_get_contents('../.env'));
        foreach ($contents as $line) {
            $exploded = explode('=', trim($line));

            if (count($exploded) === 2) {
                $config[$exploded[0]] = static::extractValue($exploded[1]);
            } else {
                $config[$exploded[0]] = null;
            }

        }

        return $config;
    }

    private static function extractValue(string $value): string
    {
        $cleaned = trim($value);

        if (!str_contains($cleaned, '"')) {
            return $cleaned;
        }

        return str_replace('"', '', $cleaned);
    }
}