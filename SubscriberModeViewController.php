<?php
/*
 * Copyright (c) 2021.
 * Marc Concepcion
 * marcanthonyconcepcion@gmail.com
 */

const SQLDuplicateKeyErrorCode = 23000;

use JetBrains\PhpStorm\ArrayShape;

require_once 'ModelViewController.php';
require_once 'DatabaseRecords.php';
require_once 'PDODatabaseRecords.php';


class SubscriberModel implements Model
{
    private DatabaseRecords $records;

    public function __construct(DatabaseRecords $records)
    {
        $this->records = $records;
    }

    function create(StdClass $model)
    {
        $this->records->edit('insert into `subscribers` (`email_address`, `last_name`, `first_name`) '
            .'values (?, ?, ?)', [$model->email_address, $model->last_name, $model->first_name]);
    }

    function retrieve(int $id)
    {
        return $this->records->fetch('select * from `subscribers` where `index`= :index',[':index'=>$id])->current();
    }

    function list() : Generator
    {
        return $this->records->fetch('select * from `subscribers`');
    }

    function update(int $id, StdClass $model)
    {
        $parameters = [];
        $variables = [];
        foreach($model as $variable=>$value)
        {
            array_push($variables,'`'.$variable.'`= :'.$variable);
            $parameters[':'.$variable.''] = $value;
        }
        $parameters[':index'] = $id;
        $query = 'update `subscribers` set '.implode ( ', ', $variables).' where `index`= :index';
        $this->records->edit($query, $parameters);
    }

    function delete(int $id)
    {
        $this->records->edit('delete from `subscribers` where `index`= :index', [':index'=>$id]);
    }

    function checkExistence(int $id) : bool
    {
        return 0 < current($this->records->fetch(
            'select count(*) from `subscribers` where `index`= :index',[':index'=>$id])->current());
    }
}


class SubscriberController extends Controller
{
    function __construct(SubscriberModel $model)
    {
        $this->register($model, 'subscribers');
    }

    /**
     * @param int|null $id
     * @return object
     * @throws HTTPNotFoundError
     */
    function get(?int $id = null): object
    {
        $model = [];
        if (is_null($id))
        {
            $records = $this->models['subscribers']->list();
            foreach($records as $record)
            {
                array_push($model, $record);
            }
            if (0 === count($model))
            {
                return (object)['status'=>(object)HTTP_NO_CONTENT];
            }
        }
        else
        {
            $model = $this->models['subscribers']->retrieve($id);
            if (false === $this->models['subscribers']->checkExistence($id))
            {
                throw new HTTPNotFoundError('Subscriber does not exist.');
            }
        }
        return (object)['status'=>(object)HTTP_OK, 'body'=>json_encode($model)];
    }

    /**
     * @param string $json_parameters
     * @return object
     * @throws HTTPConflictError
     */
    #[ArrayShape(['status_header' => "string", 'status_code' => "int", 'body' => "mixed"])]
    function post(string $json_parameters): object
    {
        try
        {
            $model = json_decode($json_parameters);
            $this->models['subscribers']->create($model);
            return (object)['status' => (object)HTTP_CREATED, 'body' => json_decode(json_encode(
                '{"success": "Record created.", "subscriber":' . json_encode((array)$model) . '}'))];
        }
        catch (Exception $error)
        {
            if(SQLDuplicateKeyErrorCode === $error->getCode())
            {
                throw new HTTPConflictError('Posting/creating an already existing record. '
                    .'Please put/update an existing record or post/create a totally new record.');
            }
            else
            {
                throw $error;
            }
        }
    }

    /**
     * @param int $id
     * @param string $json_parameters
     * @return object
     * @throws HTTPNotFoundError
     */
    #[ArrayShape(['status_header' => "string", 'status_code' => "int", 'body' => "mixed"])]
    function put(int $id, string $json_parameters): object
    {
        if (false === $this->models['subscribers']->checkExistence($id))
        {
            throw new HTTPNotFoundError('Subscriber does not exist.');
        }
        $model = json_decode($json_parameters);
        $this->models['subscribers']->update($id, $model);
        return (object)['status'=>(object)HTTP_OK, 'body'=> json_decode(json_encode(
            '{"success": "Record of subscriber # '.$id.' updated.", "updates":'
            .json_encode((array)$model).'}'))];
    }

    /**
     * @param int $id
     * @return object
     * @throws HTTPNotFoundError
     */
    #[ArrayShape(['status_header' => "string", 'status_code' => "int", 'body' => "mixed"])]
    function delete(int $id): object
    {
        if (false === $this->models['subscribers']->checkExistence($id))
        {
            throw new HTTPNotFoundError('Subscriber does not exist.');
        }
        $this->models['subscribers']->delete($id);
        return (object)['status'=>(object)HTTP_OK, 'body'=> json_decode(json_encode(
            '{"success": "Record of subscriber # '.$id.' deleted."}'))];
    }
}
