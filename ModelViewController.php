<?php
/*
 * Copyright (c) 2021.
 * Marc Concepcion
 * marcanthonyconcepcion@gmail.com
 */

$configuration = yaml_parse_file(__DIR__.'\resources\MarcPHPRESTAPIDemo.yaml');
define('ModelViewController_RESOURCE', $configuration['mvc']['resource']);
define('HTTP_OK',['code'=>200, 'message'=>'HTTP/1.1 200 OK']);
define('HTTP_CREATED',['code'=>201,'message'=>'HTTP/1.1 201 Created']);
define('HTTP_NO_CONTENT',['code'=>204,'message'=>'HTTP/1.1 204 No Content']);
define('HTTP_BAD_REQUEST',['code'=>400,'message'=>'HTTP/1.1 400 Bad Request']);
define('HTTP_NOT_FOUND',['code'=>404,'message'=>'HTTP/1.1 404 Not Found']);
define('HTTP_NOT_ALLOWED',['code'=>405,'message'=>'HTTP/1.1 405 Method Not Allowed']);
define('HTTP_CONFLICT_ERROR',['code'=>409,'message'=>'HTTP/1.1 409 Conflict Error']);
define('HTTP_INTERNAL_SERVER_ERROR',['code'=>500,'message'=>'HTTP/1.1 500 Internal Server Error']);


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
     * @throws HTTPBadRequestError
     */
    static function receiveRequest(): object
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        if (key_exists(1, $uri) && strlen($uri[1]))
        {
            if ($uri[1] !== ModelViewController_RESOURCE)
            {
                throw new HTTPBadRequestError('Resource '.$uri[1].' does not exist. Please provide a valid REST API resource.');
            }
        }
        else
        {
            throw new HTTPBadRequestError('No resource specified on the URL. Please provide a valid REST API resource.');
        }
        $id = null;
        if (key_exists(2, $uri) && strlen($uri[2]))
        {
            $id = intval($uri[2]);
        }
        $request = new StdClass();
        $request->http_command = $_SERVER["REQUEST_METHOD"];
        if (key_exists('QUERY_STRING', $_SERVER))
        {
            parse_str($_SERVER['QUERY_STRING'], $parameters);
            $request->json_parameters = json_encode($parameters);
        }
        return (object)['id'=>$id, 'request'=>$request];
    }

    static function sendResponse(StdClass $response)
    {
        header($response->status->message, true, $response->status->code);
        if(is_null($response->body)===false)
        {
            echo $response->body;
        }
    }

    static function sendErrorResponse(StdClass $http_error_status, string $message)
    {
        header($http_error_status->message, true, $http_error_status->code);
        echo json_encode(json_decode('{"error": "'.$message.'"}'));
    }
}

abstract class Controller
{
    protected array $models;

    /**
     * @param int|null $id
     * @throws HTTPNotFoundError
     */
    abstract function get(?int $id = null);
    abstract function post(string $json_parameters);

    /**
     * @param int $id
     * @param string $json_parameters
     * @throws HTTPNotFoundError
     */
    abstract function put(int $id, string $json_parameters);

    /**
     * @param int $id
     * @throws HTTPNotFoundError
     */
    abstract function delete(int $id);

    function register(Model $model, string $modelName)
    {
        $this->models[$modelName] = $model;
    }

    /**
     * @param StdClass $request
     * @param int|null $id
     * @return object
     * @throws HTTPMethodNotAllowedError
     * @throws HTTPNotFoundError
     * @throws HTTPConflictError
     */
    function processHTTP(StdClass $request, ?int $id = null): object
    {
        $this->evaluateHTTPCommand($request, $id);
        return match ($request->http_command) {
            'GET' => $this->get($id),
            'POST' => $this->post($request->json_parameters),
            'PUT' => $this->put($id, $request->json_parameters),
            'DELETE' => $this->delete($id),
            default => (object)['status'=>(object)HTTP_NOT_ALLOWED,
                'body'=>json_encode(json_decode('{"error": "Cannot use '.$request->http_command.' command." }'))]
        };
    }

    /**
     * @param StdClass $request
     * @param int|null $id
     * @throws HTTPMethodNotAllowedError
     */
    private function evaluateHTTPCommand(StdClass $request, ?int $id)
    {
        if (    false === in_array($request->http_command, ['GET', 'POST', 'PUT', 'DELETE']) ||
                $request->http_command === 'POST'   &&  !is_null($id)  ||
                $request->http_command === 'PUT'    &&   is_null($id)  ||
                $request->http_command === 'DELETE' &&   is_null($id)    )
        {
            throw new HTTPMethodNotAllowedError('HTTP command '.$request->http_command.' '.(is_null($id)?'without': 'with')
                .' specified ID is not allowed. Please provide an acceptable HTTP command.');
        }

        if (($request->http_command === 'POST' || $request->http_command === 'PUT')
            && false === key_exists('json_parameters', (array)$request))
        {
            throw new HTTPMethodNotAllowedError('HTTP command '.$request->http_command
                .' without providing parameters is not allowed. Please provide an acceptable HTTP command.');
        }
    }
}

class HTTPNotFoundError extends Exception { }
class HTTPBadRequestError extends Exception { }
class HTTPMethodNotAllowedError extends Exception { }
class HTTPConflictError extends Exception { }
