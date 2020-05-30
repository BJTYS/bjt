<?php

declare(strict_types=1);

use Jenssegers\Blade\Blade;
use Zend\Diactoros\Response\HtmlResponse;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

include '../vendor/autoload.php';

session_start();

$request = \Zend\Diactoros\ServerRequestFactory::fromGlobals();

function view($view, array $data = []): HtmlResponse
{
    $blade = new Blade('../views', '../cache');
    return (new HtmlResponse($blade->make($view, $data)->render()));
}

function sendResponse(\Zend\Diactoros\Response $response): void
{
    header(sprintf(
        'HTTP/%s %d %s',
        $response->getProtocolVersion(),
        $response->getStatusCode(),
        $response->getReasonPhrase(),
    ));

    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf(
                '%s: %s',
                $name,
                $value
            ), false);
        }
    }

    echo $response->getBody()->getContents();
}

function url(array $params = []): string
{
    global $request;

    return '?' . http_build_query(array_merge($request->getQueryParams(), $params));
}

function isAuth(): bool
{
    return $_SESSION['isLogged'] === true;
}

function setActionStatus(string $status): void
{
    $_SESSION['actionStatus'] = $status;
}

function getActionStatus(): ?string
{
    $return = $_SESSION['actionStatus'];
    unset($_SESSION['actionStatus']);
    return $return;
}


$capsule = new Capsule;

$capsule->addConnection([
    'driver'   => 'sqlite',
    'database' => '../beeJeeTest.sqlite',
]);

$capsule->setEventDispatcher(new Dispatcher(new Container));

$capsule->setAsGlobal();
$capsule->bootEloquent();

include '../Routing.php';

