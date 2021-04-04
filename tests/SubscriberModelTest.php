<?php
/*
 * Copyright (c) 2021.
 * Marc Concepcion
 * marcanthonyconcepcion@gmail.com
 */

require_once __DIR__ . "/../PDODatabaseRecords.php";
require_once __DIR__ . "/../SubscriberModeViewController.php";


use PHPUnit\Framework\TestCase;

class SubscriberModelTest extends TestCase
{
    private SubscriberModel $dut;
    private array $expected_records;

    protected function setUp(): void
    {
        $this->dut = new SubscriberModel(PDODatabaseRecords::get());
        $this->dut->create((object)['email_address'=>'marcanthonyconcepcion@gmail.com',
                                    'last_name'=>'Concepcion','first_name'=>'Marc Anthony']);
        $this->dut->create((object)['email_address'=>'marcanthonyconcepcion@email.com',
                                    'last_name'=>'Concepcion','first_name'=>'Marc']);
        $this->dut->create((object)['email_address'=>'kevin.andrews@email.com',
                                    'last_name'=>'Andrews','first_name'=>'Kevin']);
        $this->expected_records = [
            (object)['index'=>1,    'email_address'=>'marcanthonyconcepcion@gmail.com',
                'last_name'=>'Concepcion',  'first_name'=>'Marc Anthony',   'activation_flag'=> 0 ],
            (object)['index'=>2,    'email_address'=>'marcanthonyconcepcion@email.com',
                'last_name'=>'Concepcion',  'first_name'=>'Marc',           'activation_flag'=> 0 ],
            (object)['index'=>3,    'email_address'=>'kevin.andrews@email.com',
                'last_name'=>'Andrews',     'first_name'=>'Kevin',          'activation_flag'=> 0 ],
        ];
    }

    protected function tearDown() : void
    {
        unset($this->expected_records);
        PDODatabaseRecords::get()->edit('truncate table `subscribers`');
    }

    function testCreate()
    {
        $model = new StdClasS();
        $model->email_address = 'riseofskywalker@starwars.com';
        $model->last_name = 'Palpatine';
        $model->first_name = 'Rey';
        $model->activation_flag = 0;

        $this->expected_records[4-1] = new StdClass();
        $this->expected_records[4-1]->index = 4;
        $this->expected_records[4-1]->email_address = $model->email_address;
        $this->expected_records[4-1]->last_name = $model->last_name;
        $this->expected_records[4-1]->first_name = $model->first_name;
        $this->expected_records[4-1]->activation_flag = $model->activation_flag;
        $this->dut->create($model);
        $this->assertEquals($this->expected_records[4-1], (object)$this->dut->retrieve(4));
        $this->testRetrieve();
        $this->testList();
    }

    function testList()
    {
        $records = $this->dut->list();
        $index = 0;
        foreach($records as $record)
        {
            $this->assertEquals($this->expected_records[$index++], (object)$record);
        }
    }

    function testRetrieve()
    {
        for($i=0;$i<count($this->expected_records);$i++)
        {
            $this->assertEquals($this->expected_records[$i], (object)$this->dut->retrieve($i+1));
        }
    }

    function testUpdate()
    {
        $model = new StdClasS();
        $model->activation_flag = 1;
        $model->email_address = 'marcanthonyconcepcion@yeahmail.com';
        $this->expected_records[1-1]->activation_flag = $model->activation_flag;
        $this->expected_records[1-1]->email_address = $model->email_address;
        $this->dut->update(1, $model);
        $this->assertEquals($this->expected_records[1-1], (object)$this->dut->retrieve(1));
        $this->testRetrieve();
        $this->testList();
    }

    function testDelete()
    {
        unset($this->expected_records[3-1]);
        $this->expected_records = array_values($this->expected_records);
        $this->dut->delete(3);
        $this->testRetrieve();
        $this->testList();
    }

    function testCheckExistence()
    {
        $this->assertTrue($this->dut->checkExistence(2));
        $this->assertFalse($this->dut->checkExistence(200));
    }
}
