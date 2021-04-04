<?php
/*
 * Copyright (c) 2021.
 * Marc Concepcion
 * marcanthonyconcepcion@gmail.com
 */

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
