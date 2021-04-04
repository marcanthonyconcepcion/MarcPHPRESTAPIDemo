<?php
/*
 * Copyright (c) 2021.
 * Marc Concepcion
 * marcanthonyconcepcion@gmail.com
 */

use JetBrains\PhpStorm\ArrayShape;


interface Model
{
    function create(StdClass $model);
    function retrieve(int $id);
    function list();
    function update(int $id, StdClass $model);
    function delete(int $id);
    function checkExistence(int $id);
}

class View
{
    /**
     * @throws HTTPBadRequest
     */
    static function receiveRequest(): object
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        if ($uri[1] !== 'subscribers') {
            throw new HTTPBadRequest();
        }
        $id = is_null($uri[2]) ? null : intval($uri[2]);
        $request = new StdClass();
        $request->http_command = $_SERVER["REQUEST_METHOD"];
        parse_str($_SERVER['QUERY_STRING'], $parameters);
        $request->json_parameters = json_encode($parameters);
        return (object)['id'=>$id, 'request'=>$request];
    }

    static function sendResponse(StdClass $response)
    {
        header($response->status_header, true, $response->status_code);
        if(is_null($response->body)===false)
        {
            echo $response->body;
        }
    }
}

class Controller
{
    private Model $model;
    public string $request;

    function __construct(Model $model)
    {
        $this->model = $model;
    }

    function processHTTP(StdClass $request, ?int $id = null): array
    {
        return match ($request->http_command) {
            'GET' => $this->get($id),
            'POST' => $this->post($request->json_parameters),
            'PUT' => $this->put($id, $request->json_parameters),
            'DELETE' => $this->delete($id),
            default => ['status_header'=>'HTTP/1.1 405 Method Not Allowed', 'status_code'=>405,
                'body'=>json_encode(json_decode('{"error": "Cannot use '.$request->http_command.' command." }'))]
        };
    }

    function get(?int $id = null): array
    {
        try {
            $model = [];
            if ($id === null) {
                $records = $this->model->list();
                foreach($records as $record) {
                    array_push($model, $record);
                }
            }
            else {
                $model = $this->model->retrieve($id);
                if (false === $this->model->checkExistence($id))
                {
                    throw new NonExistingSubscriberError();
                }
            }
            return ['status_header'=>'HTTP/1.1 200 OK', 'status_code'=>200, 'body'=>json_encode($model)];
        }
        catch (NonExistingSubscriberError)
        {
            return ['status_header'=>'HTTP_404_NOT_FOUND', 'status_code'=>404,
                'body'=>json_decode(json_encode('{"error": "Subscriber does not exist"}'))];
        }

    }

    #[ArrayShape(['status_header' => "string", 'status_code' => "int", 'body' => "mixed"])]
    function post(string $json_parameters): array
    {
        $model = json_decode($json_parameters);
        $this->model->create($model);
        return ['status_header'=>'HTTP/1.1 201 Created', 'status_code'=>201,
            'body'=> json_decode(json_encode('{"success": "Record created.", "subscriber":'.
                json_encode((array)$model).'}'))];
    }

    #[ArrayShape(['status_header' => "string", 'status_code' => "int", 'body' => "mixed"])]
    function put(int $id, string $json_parameters): array
    {
        try
        {
            if (false === $this->model->checkExistence($id))
            {
                throw new NonExistingSubscriberError();
            }
            $model = json_decode($json_parameters);
            $this->model->update($id, $model);
            return ['status_header'=>'HTTP/1.1 200 OK', 'status_code'=>200,
                'body'=> json_decode(json_encode('{"success": "Record of subscriber # '.$id.' updated.", "updates":'.
                    json_encode((array)$model).'}'))];
        }
        catch (NonExistingSubscriberError)
        {
            return ['status_header'=>'HTTP_404_NOT_FOUND', 'status_code'=>404,
                'body'=>json_decode(json_encode('{"error": "Subscriber does not exist"}'))];
        }
    }

    #[ArrayShape(['status_header' => "string", 'status_code' => "int", 'body' => "mixed"])]
    function delete(int $id): array
    {
        try
        {
            if (false === $this->model->checkExistence($id))
            {
                throw new NonExistingSubscriberError();
            }
            $this->model->delete($id);
            return ['status_header'=>'HTTP/1.1 200 OK', 'status_code'=>200,
                'body'=> json_decode(json_encode('{"success": "Record of subscriber # '.$id.' deleted."}'))];
        }
        catch (NonExistingSubscriberError)
        {
            return ['status_header'=>'HTTP_404_NOT_FOUND', 'status_code'=>404,
                'body'=>json_decode(json_encode('{"error": "Subscriber does not exist"}'))];
        }
    }
}

class NonExistingSubscriberError extends Exception { }
class HTTPBadRequest extends Exception { }
