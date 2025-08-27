<?php

session_set_cookie_params(3600 * 24 * 31);

use Commercial\Framework\Core\Environment;
use Commercial\Framework\Core\ClassLoader;
use Commercial\Application\Application;
use Commercial\Framework\Http\Request;

require_once(__DIR__ . DIRECTORY_SEPARATOR . "framework" . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "Environment.php");

Environment::utf8();

$dotEnvFile = __DIR__ . DIRECTORY_SEPARATOR . ".env";

if (file_exists($dotEnvFile)) {
    Environment::loadEnv($dotEnvFile);
}

if (getenv("APP_ENV") === "development") {
    Environment::showErrors();
}

require_once(__DIR__ . DIRECTORY_SEPARATOR . "framework" . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "ClassLoader.php");

$loader = new ClassLoader();
$loader->addNamespace("Commercial\\Framework\\", __DIR__ . DIRECTORY_SEPARATOR . "framework");
$loader->addNamespace("Commercial\\Application\\", __DIR__ . DIRECTORY_SEPARATOR . "application");
$loader->register();

require __DIR__  . '/vendor/autoload.php';

//MercadoPago\SDK::setAccessToken("<TOKEN_HERE>");      // On Production

$application = new Application();
$application->setDirectory(__DIR__ . DIRECTORY_SEPARATOR . "application" . DIRECTORY_SEPARATOR);

$request = Request::createFromGlobals();
$response = $application->process($request);

if (is_string($response)) {
    header("Content-Type: text/html; charset=utf-8");
    echo $response;
} elseif (is_array($response) || is_object($response)) {
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($response);
} else {
    echo "Unknown response type";
}
