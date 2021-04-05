<?php
/*
 * Copyright (c) 2021.
 * Marc Concepcion
 * marcanthonyconcepcion@gmail.com
 */

$configuration = yaml_parse_file(__DIR__.'\resources\MarcPHPRESTAPIDemo.yaml');
define('Main_ERROR_LOG', $configuration['log']['filename']);

use JetBrains\PhpStorm\Pure;

require_once 'ModelViewController.php';
require_once 'SubscriberModeViewController.php';
require_once 'PDODatabaseRecords.php';


class Main
{
    private Controller $controller;
    private StdClass $request;

    #[Pure]
    function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    function execute()
    {
        try
        {
            $view_request = View::receiveRequest();
            $view_response = $this->controller->processHTTP($view_request->request, $view_request->id);
            View::sendResponse($view_response);
        }
        catch (HTTPBadRequestError $error)
        {
            View::sendErrorResponse((object)HTTP_BAD_REQUEST, $error->getMessage());
        }
        catch (HTTPMethodNotAllowedError $error)
        {
            View::sendErrorResponse((object)HTTP_NOT_ALLOWED, $error->getMessage());
        }
        catch (HTTPNotFoundError $error)
        {
            View::sendErrorResponse((object)HTTP_NOT_FOUND, $error->getMessage());
        }
    }
}

if (!debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS))
{
    try
    {
        $main = new Main(new SubscriberController(new SubscriberModel(PDODatabaseRecords::get())));
        $main->execute();
    }
    catch (Exception $error)
    {
        View::sendErrorResponse((object)HTTP_INTERNAL_SERVER_ERROR, "Error in server. Please bear with us.");
        error_log($error->getMessage() . PHP_EOL, 3, Main_ERROR_LOG);
    }
}
