<?php

require_once __DIR__ . '/../vendor/autoload.php';
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

use App\Event\GoogleListener;
use App\Event\ResponseEvent;
use Idaiq\Framework;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;


$request = Request::createFromGlobals();
$routes = include __DIR__.'/../config/router.php';

$context = new RequestContext();
$matcher = new UrlMatcher($routes, $context);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$dispatcher = new EventDispatcher();
//$dispatcher->addListener('response', function (ResponseEvent $event){
//    $response = $event->getResponse();
//    if ($response->isRedirection()
//        || ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html'))
//        || 'html' !== $event->getRequest()->getRequestFormat()
//    ) {
//        return;
//    }
//    $response->setContent($response->getContent().'GA CODE');
//});

$dispatcher->addListener('request', array(new GoogleListener(), 'onRequest'));
$dispatcher->addListener('response', array(new GoogleListener(), 'onResponse'));

$framework = new Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);
$response = $framework->handle($request);

$response->send();