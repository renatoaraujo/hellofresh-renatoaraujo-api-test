<?php
declare(strict_types=1);

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Config\FileLocator;
use HelloFresh\RuntimeContext;
use HelloFresh\Infrastructure\Subscriber\ExceptionSubscriber;
use HelloFresh\Infrastructure\Subscriber\CorsSubscriber;
use HelloFresh\Infrastructure\Subscriber\SecuritySubscriber;

require_once __DIR__ . '/src/bootstrap.php';

$loader = new YamlFileLoader(new FileLocator(__DIR__ . '/config'));
$routes = $loader->load('routes.yaml');

$request = Request::createFromGlobals();

$matcher = new UrlMatcher($routes, new RequestContext());

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));
$dispatcher->addSubscriber(new ExceptionSubscriber());
$dispatcher->addSubscriber(new CorsSubscriber(RuntimeContext::getContainer()->getParameter('application.cors')));
$dispatcher->addSubscriber(new SecuritySubscriber(
    RuntimeContext::getContainer()->get('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface'),
    RuntimeContext::getContainer()->get('Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface')
));

$controllerResolver = new ContainerControllerResolver(RuntimeContext::getContainer());
$argumentResolver = new ArgumentResolver();

$kernel = new HttpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);

$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
