<?php
/*
 * Copyright (c) 2021.
 * Marc Concepcion
 * marcanthonyconcepcion@gmail.com
 */


interface DatabaseRecords
{
    function fetch(string $query, array $parameters=[]): Generator;
    function edit(string $query, array $parameters=[]);
}
