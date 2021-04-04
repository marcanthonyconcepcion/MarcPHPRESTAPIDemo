<?php
/*
 * Copyright (c) 2021.
 * Marc Concepcion
 * marcanthonyconcepcion@gmail.com
 */

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
            View::sendResponse((object)$view_response);
        }
        catch (HTTPBadRequest)
        {
            header('HTTP/1.1 400 Bad Request', 400);
        }
    }
}

$main = new Main(new Controller(new SubscriberModel(PDODatabaseRecords::get())));
$main->execute();
