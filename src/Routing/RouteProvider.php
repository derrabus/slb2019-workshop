<?php

namespace TravelOrganizer\Routing;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use TravelOrganizer\Controller\LegacyBridgeController;

final class RouteProvider
{
    private $controllerPath;

    public function __construct(string $controllerPath)
    {
        $this->controllerPath = $controllerPath;
    }

    public function loadRoutes(): RouteCollection
    {
        $routes = new RouteCollection();

        $finder = (new Finder())
            ->in($this->controllerPath)
            ->name('*Controller.php');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $reflectionClass = new \ReflectionClass(substr($file->getFilename(), 0, -4));

            preg_match('/^(.*)Controller$/', $reflectionClass->getName(), $matches);
            $controller = strtolower($matches[1]);

            foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
                if (!preg_match('/^(.*)Action$/', $reflectionMethod->getName(), $matches)) {
                    continue;
                }

                $action = strtolower($matches[1]);

                $routes->add(sprintf('zend.%s.%s', $controller, $action), new Route(
                    sprintf('/%s/%s/{suffix}', $controller, $action),
                    [
                        'zendController' => $controller,
                        'zendAction' => $action,
                        '_controller' => LegacyBridgeController::class,
                    ],
                    [
                        'suffix' => '.*'
                    ]
                ));

                if ($action !== 'index') {
                    continue;
                }

                $routes->add(sprintf('zend.%s', $controller), new Route(
                    sprintf('/%s', $controller),
                    [
                        'zendController' => $controller,
                        'zendAction' => $action,
                        '_controller' => LegacyBridgeController::class,
                    ]
                ));

                if ($controller !== 'index') {
                    continue;
                }

                $routes->add('zend', new Route(
                    '/',
                    [
                        'zendController' => $controller,
                        'zendAction' => $action,
                        '_controller' => LegacyBridgeController::class,
                    ]
                ));
            }
        }

        return $routes;
    }
}
