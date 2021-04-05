<?php ob_start();
/*
 * Copyright (c) 2021.
 * Marc Concepcion
 * marcanthonyconcepcion@gmail.com
 */

require_once 'ModelViewController.php';
require_once 'SubscriberModeViewController.php';
require_once 'PDODatabaseRecords.php';


use PHPUnit\Framework\TestCase;

class SubscriberControllerTest extends TestCase
{
    private Controller $dut;
    private array $expected_records;

    protected function setUp(): void
    {
        $this->expected_records = [
            (object)['index'=>1,    'email_address'=>'marcanthonyconcepcion@gmail.com',
                'last_name'=>'Concepcion',  'first_name'=>'Marc Anthony',   'activation_flag'=> 0 ],
            (object)['index'=>2,    'email_address'=>'marcanthonyconcepcion@protonmail.com',
                'last_name'=>'Concepcion',  'first_name'=>'Marc',           'activation_flag'=> 0 ],
            (object)['index'=>3,    'email_address'=>'kevin.andrews@yahoomail.com',
                'last_name'=>'Andrews',     'first_name'=>'Kevin',          'activation_flag'=> 0 ],
        ];
        $this->dut = new SubscriberController(new SubscriberModel(PDODatabaseRecords::get()));
        $this->dut->post('{"email_address": "marcanthonyconcepcion@gmail.com", "last_name":"Concepcion", "first_name":"Marc Anthony"}');
        $this->dut->post('{"email_address": "marcanthonyconcepcion@protonmail.com", "last_name":"Concepcion", "first_name":"Marc"}');
        $this->dut->post('{"email_address": "kevin.andrews@yahoomail.com", "last_name":"Andrews", "first_name":"Kevin"}');
    }

    protected function tearDown() : void
    {
        unset($this->expected_records);
        PDODatabaseRecords::get()->edit('truncate table `subscribers`');
    }

    function testGet()
    {
        $this->assertEquals((object)['status'=>(object)HTTP_OK,'body'=>json_encode($this->expected_records)],
            $this->dut->processHTTP((object)['http_command'=>'GET','parameters'=>json_encode(new StdClass())]));

        for($i=0;$i<count($this->expected_records);$i++)
        {
            $this->assertEquals((object)['status'=>(object)HTTP_OK,'body'=>json_encode($this->expected_records[$i])],
              $this->dut->processHTTP((object)['http_command'=>'GET','parameters'=>json_encode(new StdClass())],$i+1));
        }
    }

    function testPost()
    {
        $model = new StdClass();
        $model->email_address = 'riseofskywalker@starwars.com';
        $model->last_name = 'Palpatine';
        $model->first_name = 'Rey';
        $model->activation_flag = 0;

        $id = 4;
        $this->expected_records[$id-1] = new StdClass();
        $this->expected_records[$id-1]->index = $id;
        $this->expected_records[$id-1]->email_address = $model->email_address;
        $this->expected_records[$id-1]->last_name = $model->last_name;
        $this->expected_records[$id-1]->first_name = $model->first_name;
        $this->expected_records[$id-1]->activation_flag = $model->activation_flag;
        $this->assertEquals((object)['status'=>(object)HTTP_CREATED,
            'body'=>'{"success": "Record created.", "subscriber":'.json_encode((array)$model).'}'],
            $this->dut->processHTTP((object)['http_command'=>'POST', 'json_parameters'=>json_encode($model)]));
        $this->testGet();
    }

    function testUpdate()
    {
        $id = 1;
        $model = new StdClass();
        $model->activation_flag = 1;
        $model->email_address = 'marcanthonyconcepcion@yahoo.com';
        $this->expected_records[$id-1]->activation_flag = $model->activation_flag;
        $this->expected_records[$id-1]->email_address = $model->email_address;
        $this->assertEquals((object)['status'=>(object)HTTP_OK,'body'=>
            '{"success": "Record of subscriber # '.$id.' updated.",'.' "updates":'.json_encode((array)$model).'}'],
            $this->dut->processHTTP((object)['http_command'=>'PUT', 'json_parameters'=>json_encode($model)], $id));
        $this->assertEquals((object)['status'=>(object)HTTP_OK,'body'=>json_encode($this->expected_records[$id-1])],
                            $this->dut->processHTTP((object)['http_command'=>'GET'], $id));
        $this->testGet();
    }

    function testDelete()
    {
        $id=3;
        unset($this->expected_records[$id-1]);
        $this->expected_records = array_values($this->expected_records);
        $this->assertEquals((object)['status'=>(object)HTTP_OK,'body'=>'{"success": "Record of subscriber # '.$id.' deleted."}'],
            $this->dut->processHTTP((object)['http_command'=>'DELETE'], $id));
        $this->testGet();
    }

    function testHTTPNotFoundError()
    {
        $this->expectException(HTTPNotFoundError::class);
        $this->dut->processHTTP((object)['http_command'=>'GET'], 100);
        $this->dut->processHTTP((object)['http_command'=>'PUT', 'json_parameters'=>json_encode(new StdClass())], 200);
        $this->dut->processHTTP((object)['http_command'=>'DELETE'], 300);
    }

    function testHTTPMethodNotAllowedError()
    {
        $this->expectException(HTTPMethodNotAllowedError::class);
        $this->dut->processHTTP((object)['http_command'=>'TRACE']);
        $this->dut->processHTTP((object)['http_command'=>'POST', 'json_parameters'=>json_encode(new StdClass())], 200);
        $this->dut->processHTTP((object)['http_command'=>'DELETE']);
    }
}
