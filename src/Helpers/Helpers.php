<?php

use JetBrains\PhpStorm\NoReturn;
use Spaede\Support\Application;
use Spaede\Support\Engine\ConfigReader;
use Spaede\Support\Engine\EnvParser;
use Spaede\Support\Request;
use Spaede\Support\Response;
use Spaede\Support\View;

#[NoReturn] function dd(...$args): void
{
    $backtrace = debug_backtrace();
    $last_trace = $backtrace[0];

    echo '<pre style="background: #f5f5f5;  font-size: 15px; border: 1px solid #ddd; padding: 1em; margin: 1em 0; border-radius: 5px; overflow: auto; word-wrap: normal;">';

    foreach ($args as $arg) {
        if (is_array($arg)) {
            print_r($arg);
        }
        elseif (is_object($arg)) {
            print_r((array)$arg);
        }
        else {
            echo var_dump($arg);
        }
    }
    echo '</pre>';

    echo '<p>';
    echo $last_trace['file'].' <strong>Line: </strong>' . $last_trace['line'];
    echo '</p>';

    die(1);
}

function app(): ?Application
{
    return Application::$instance;
}

function view(string $path, array $data): View
{
    return response()->view($path, $data);
}

function request(): Request
{
    return Application::$instance->request();
}

function response(): Response
{
    return new Response();
}


/**
 * Retrieves the value of the given key from the environment configuration.
 *
 * @param string $key The key to retrieve the value for.
 * @param mixed|null $default The default value to return if the key is not found (optional).
 * @return mixed The value of the key from the environment configuration, or the default value if the key is not found.
 */
function env(string $key, mixed $default = null): mixed
{
    return EnvParser::getConfig($key) ?? $default;
}

function access_array_by_dot(string $key, array $array)
{
    $parts = explode('.', $key);

    if (!$parts) {
        return null;
    }

    global $tempVar;

    $count = 0;
    foreach ($parts as $part) {

        // For initial round
        if (($count === 0) && isset($array[$part])) {
            $tempVar = $array[$part];
        } elseif (($count === 0) && !isset($array[$part])) {
            $tempVar = null;
            break;
        }

        if (isset($tempVar[$part])) {
            $tempVar = $tempVar[$part];
        }

        $count++;
    }

    $value = $tempVar;

    unset($tempVar);

    return $value;
}

function config($key, $default = null)
{
    return ConfigReader::getConfig($key) ?? $default;
}