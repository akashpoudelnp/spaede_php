<?php

namespace Spaede\Support;

use Closure;
use Exception;
use Spaede\Contracts\Response;
use function Spaede\Helpers\dd;

class Routing
{
    private Request $request;

    public function __construct(private Application $application)
    {
        $this->request = $this->application->request();
    }

    private function getCurrentRoute(): array
    {
        $appRoutes = Router::getRoutes();

        $currentURI = $this->request->uri();

        $simpleRoute = $appRoutes[$currentURI] ?? null;

        if ($simpleRoute) {
            return $simpleRoute;
        }

        $routePaths = array_keys($appRoutes);

        foreach ($routePaths as $routePath) {
            // Regex pattern to match {parameter}
            $pattern = '/\{([a-z]+)\}/';
            $replacement = '([a-zA-Z0-9\_\-]+)';

            // Replace {parameter} with regex pattern in route
            $routePattern = preg_replace($pattern, $replacement, $routePath);

            // Route pattern now matches URLs with any value in place of {parameter}
            if (preg_match("#^{$routePattern}$#", $currentURI, $matches)) {
                // Remove the first match
                array_shift($matches);

                // Use the matches as parameters for the route.
                // You could modify this part to pass these parameters to your route handling method, for example.
                $params = $matches;

                // Now return the matched route with parameters
                $currentRoute = $appRoutes[$routePath];
                $currentRoute['params'] = $params;
                return $currentRoute;
            }
        }

        throw new Exception('Route Not Found');
    }

    public function resolve()
    {
        include "../routes/web.php";

        $currentRoute = $this->getCurrentRoute();

        if ($currentRoute['method'] !== strtoupper($this->request->method())) {
            throw new Exception(
                sprintf(
                    "%s method is not supported for route: %s",
                    $this->request->method(),
                    $this->request->uri(),
                )
            );
        }

        if ($currentRoute['action'] instanceof Closure) {
            return $this->processClosureRoutes($currentRoute);
        }

        if (is_array($currentRoute['action'])) {
            $this->processArrayRoutes($currentRoute);
        }

    }

    private function processClosureRoutes(array $currentRoute)
    {
        return $currentRoute['action']();
    }

    private function processArrayRoutes(array $currentRoute)
    {
        if (count($currentRoute['action']) === 2) {
            $this->processControllerRoutes($currentRoute['action'][0], $currentRoute['action'][1],
                $currentRoute['params'] ?? []);
        }

    }

    private function processControllerRoutes($controller, $method, $params = [])
    {
        if (!class_exists($controller)) {
            throw new Exception(
                sprintf(
                    "Unable to find the controller %s::class",
                    $controller
                )
            );
        }

        $instance = new $controller($this->application);

        if (get_parent_class($instance) !== BaseController::class) {
            throw new Exception(
                sprintf(
                    "Controller %s::class must extend the BaseController::class",
                    $controller
                )
            );
        }

        if (!method_exists($instance, $method)) {
            throw new Exception(
                sprintf(
                    "Controller %s::class doesnt have method named '%s'",
                    $controller,
                    $method
                )
            );
        }

        if ($params) {
            $output = $instance->$method(...$params);
        } else {
            $output = $instance->$method();
        }

        if ($output instanceof Response) {
            echo $output->render();
            return;
        }

        if (is_string($output)) {
            echo $output;
            return;
        }

        dd('Dont know how to handle');
    }

}