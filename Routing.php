<?php

declare(strict_types=1);

use App\Controller;

$dispatcher = FastRoute\simpleDispatcher(static function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', [Controller::class, 'main']);
    $r->addRoute('POST', '/', [Controller::class, 'addTask']);
    $r->addRoute('POST', '/edit', [Controller::class, 'editTask']);
    $r->addRoute('POST', '/login', [Controller::class, 'login']);
    $r->addRoute('GET', '/logout', [Controller::class, 'logout']);
});

$httpMethod = $request->getMethod();
$uri = $request->getUri()->getPath();

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $class = $handler[0];
        $cmethod = $handler[1];
        $vars = $routeInfo[2];

        /** @var \Zend\Diactoros\Response $response */
        $response = (new $class)->$cmethod($request);

        sendResponse($response);

        break;
}