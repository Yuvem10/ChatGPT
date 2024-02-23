<?php

use CodeIgniter\Router\RouteCollection;

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 * The RouteCollection object allows you to modify the way that the
 * Router works, by acting as a holder for it's configuration settings.
 * The following methods can be called on the object to modify
 * the default operations.
 *
 *    $routes->defaultNamespace()
 *
 * Modifies the namespace that is added to a controller if it doesn't
 * already have one. By default this is the global namespace (\).
 *
 *    $routes->defaultController()
 *
 * Changes the name of the class used as a controller when the route
 * points to a folder instead of a class.
 *
 *    $routes->defaultMethod()
 *
 * Assigns the method inside the controller that is ran when the
 * Router is unable to determine the appropriate method to run.
 *
 *    $routes->setAutoRoute()
 *
 * Determines whether the Router will attempt to match URIs to
 * Controllers when no specific route has been defined. If false,
 * only routes that have been defined here will be available.
 */
/** @var RouteCollection $routes */
$routes->setDefaultNamespace('');
$routes->setDefaultController(INTEGRATION_BASE_MODULE.'\Controllers\C_ChatGPT');
$routes->setDefaultMethod('displayChatGPT');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', INTEGRATION_BASE_MODULE.'\Controllers\C_ChatGPT::displayChatGPT');

//--------------------------------------------------------------------
// CHAT GPT
//--------------------------------------------------------------------

$controllerPath = INTEGRATION_BASE_MODULE.'\Controllers\C_ChatGPT';

/** @uses \ChatGPT\Controllers\C_ChatGPT::loadChatCompletionSession() */
$routes->get('sessions/(:any)', $controllerPath . '::loadChatCompletionSession/$1');

/**  @uses \ChatGPT\Controllers\C_ChatGPT::getChatCompletionSessions() */
$routes->get('sessions', $controllerPath . '::getChatCompletionSessions');

/** @uses \ChatGPT\Controllers\C_ChatGPT::createChatCompletionSession() */
$routes->post('sessions', $controllerPath . '::createChatCompletionSession');

/** @uses \ChatGPT\Controllers\C_ChatGPT::deleteChatCompletionSession() */
$routes->delete('sessions/(:any)', $controllerPath . '::deleteChatCompletionSession/$1');

/** @uses \ChatGPT\Controllers\C_ChatGPT::displayChatGPT() */
$routes->get('display', $controllerPath . '::displayChatGPT');

/** @uses \ChatGPT\Controllers\C_ChatGPT::createChatCompletion() */
$routes->post('createCompletion', $controllerPath . '::createChatCompletion');
