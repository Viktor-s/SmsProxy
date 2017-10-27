<?php

namespace SmsProxy\Web;

use SmsProxy\SendSmsController;
use SmsProxy\SmsSenderFactory;
use Symfony\Component\HttpFoundation\Request;

include_once __DIR__.'/../vendor/autoload.php';

$factory = new SmsSenderFactory(dirname(__DIR__).'/var/logs');
$sender = $factory->create();
$controller = new SendSmsController($sender);

$request = Request::createFromGlobals();

$response = $controller->handleAction($request);

$response->send();
