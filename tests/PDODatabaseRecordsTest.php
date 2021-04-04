<?php
/*
 * Copyright (c) 2021.
 * Marc Concepcion
 * marcanthonyconcepcion@gmail.com
 */

require_once __DIR__ . "/../DatabaseRecords.php";
require_once __DIR__ . "/../PDODatabaseRecords.php";


class PDODatabaseRecordsTest extends PHPUnit\Framework\TestCase
{
    private DatabaseRecords $dut;
    private array $expected_records;
    private array $expected_configuration;

    protected function setUp(): void
    {
        $this->expected_configuration = [
            'database'=> [  'host'=> 'localhost', 'dbname'=> 'subscribers_database',
                'user'=> 'user', 'password'=> 'password'    ]
        ];
        $this->expected_records = [
            (object)['index'=>1,    'email_address'=>'marcanthonyconcepcion@gmail.com',
                                    'last_name'=>'Concepcion',  'first_name'=>'Marc Anthony',   'activation_flag'=> 0 ],
            (object)['index'=>2,    'email_address'=>'marcanthonyconcepcion@email.com',
                                    'last_name'=>'Concepcion',  'first_name'=>'Marc',           'activation_flag'=> 1 ],
            (object)['index'=>3,    'email_address'=>'kevin.andrews@email.com',
                                    'last_name'=>'Andrews',     'first_name'=>'Kevin',          'activation_flag'=> 0 ],
        ];
        $this->dut = PDODatabaseRecords::get();
        $this->dut->edit('insert into `subscribers` (`email_address`, `last_name`, `first_name`, `activation_flag`) '
            .'values (?, ?, ?, ?)', ['marcanthonyconcepcion@gmail.com', 'Concepcion', 'Marc Anthony', 0]);
        $this->dut->edit('insert into `subscribers` (`email_address`, `last_name`, `first_name`, `activation_flag`) '
            .'values (?, ?, ?, ?)', ['marcanthonyconcepcion@email.com', 'Concepcion', 'Marc', 1]);
        $this->dut->edit('insert into `subscribers` (`email_address`, `last_name`, `first_name`, `activation_flag`) '
            .'values (?, ?, ?, ?)', ['kevin.andrews@email.com', 'Andrews', 'Kevin', 0]);
    }

    protected function tearDown() : void
    {
        unset($this->expected_records);
        $this->dut->edit('truncate table `subscribers`');
    }

    function testConfigInDatabase()
    {
        $this->assertEquals(Database_HOST, $this->expected_configuration['database']['host']);
        $this->assertEquals(Database_DBNAME, $this->expected_configuration['database']['dbname']);
        $this->assertEquals(Database_USER, $this->expected_configuration['database']['user']);
        $this->assertEquals(Database_PASSWORD, $this->expected_configuration['database']['password']);
    }

    function testConnection()
    {
        $records = $this->dut->fetch('select :number', [':number'=>1]);
        $this->assertEquals(1,current($records->current()));
    }

    function testCreate()
    {
        $this->expected_records[3] = new StdClass();
        $this->expected_records[3]->index = 4;
        $this->expected_records[3]->email_address = 'riseofskywalker@starwars.com';
        $this->expected_records[3]->last_name = 'Palpatine';
        $this->expected_records[3]->first_name = 'Rey';
        $this->expected_records[3]->activation_flag = 0;
        $this->dut->edit('insert into `subscribers` '
            .'(`email_address`, `last_name`, `first_name`, `activation_flag`)  values (?, ?, ?, ?)',
            ['riseofskywalker@starwars.com', 'Palpatine', 'Rey', 0]);
        $this->testRetrieve();
    }

    function testRetrieve()
    {
        $records = $this->dut->fetch('select * from `subscribers`');
        $index = 0;
        foreach($records as $record)
        {
            $this->assertEquals($this->expected_records[$index++], (object)$record);
        }
    }

    function testUpdate()
    {
        $this->expected_records[0]->activation_flag = 1;
        $this->dut->edit('update `subscribers` set `activation_flag`= :activation_flag '
                                                        .'where `email_address`= :email_address',
                                        [':activation_flag'=>1, ':email_address'=>"marcanthonyconcepcion@gmail.com"]);
        $this->testRetrieve();
    }

    function testDelete()
    {
        unset($this->expected_records[2]);
        $this->expected_records = array_values($this->expected_records);
        $this->dut->edit('delete from `subscribers` where `email_address`= :email_address',
                                                        [':email_address'=>'kevin.andrews@email.com']);
        $this->testRetrieve();
    }
}
