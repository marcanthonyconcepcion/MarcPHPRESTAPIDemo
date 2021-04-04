<?php
/*
 * Copyright (c) 2021.
 * Marc Concepcion
 * marcanthonyconcepcion@gmail.com
 */

$configuration = yaml_parse_file(__DIR__.'\resources\MarcPHPRESTAPIDemo.yaml');
define('Database_HOST', $configuration['database']['host']);
define('Database_DBNAME', $configuration['database']['dbname']);
define('Database_USER', $configuration['database']['user']);
define('Database_PASSWORD', $configuration['database']['password']);
define('Database_CHARSET', 'utf8');

require_once 'DatabaseRecords.php';


class PDODatabaseRecords implements DatabaseRecords
{
    private ?PDO $database;

    function __construct()
    {
        try
        {
            $this->database = new PDO('mysql:host='.Database_HOST.';dbname='.Database_DBNAME.';charset='.Database_CHARSET,
                Database_USER, Database_PASSWORD, [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                                                     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                                                     PDO::ATTR_EMULATE_PREPARES => false ]);
        }
        catch (PDOException $exception)
        {
            throw new ConnectDatabaseError($exception->getMessage());
        }
    }

    function fetch(string $query, array $parameters=[]): Generator
    {
        $statement = $this->database->prepare($query);
        try
        {
            $statement->execute($parameters);
            while($row = $statement->fetch())
                yield $row;
        }
        catch (PDOException $exception)
        {
            throw new RunDatabaseQueryError($exception->getMessage());
        }
        finally
        {
            $statement->closeCursor();
        }
    }

    function edit(string $query, array $parameters=[])
    {
        try
        {
            $this->database->prepare($query)->execute($parameters);
        }
        catch (PDOException $exception)
        {
            throw new RunDatabaseQueryError($exception->getMessage());
        }
    }

    private static ?PDODatabaseRecords $singleton = null;
    static function get(): PDODatabaseRecords
    {
        if (null === PDODatabaseRecords::$singleton)
            PDODatabaseRecords::$singleton = new self;
        return PDODatabaseRecords::$singleton;
    }
}

class RunDatabaseQueryError extends Exception { }
class ConnectDatabaseError extends Exception { }
