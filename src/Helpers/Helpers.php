<?php

use Spaede\Support\Application;
use Spaede\Support\Engine\ConfigReader;
use Spaede\Support\Engine\EnvParser;
use Spaede\Support\Request;
use Spaede\Support\Response;
use Spaede\Support\View;

function dd(...$args): void
{
    echo '<pre>';
    print_r(var_export(func_get_args()));
    echo '</pre>';
    die;
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
 * @param  string  $key  The key to retrieve the value for.
 * @param  mixed|null  $default  The default value to return if the key is not found (optional).
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
        }else{
            $tempVar = null;
            break;
        }

        if (isset($tempVar[$part])) {
            $tempVar = $tempVar[$part];
        }else{
            $tempVar = null;
            break;
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