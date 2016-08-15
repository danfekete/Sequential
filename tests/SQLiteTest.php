<?php
use danfekete\Sequential\Bucket;
use danfekete\Sequential\DataProviders\SQLite;

/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */
class SQLiteTest extends PHPUnit_Framework_TestCase
{

    public function testGetLastID()
    {
        $data = new SQLite();
        $bucket = new Bucket(1, 'a', 2);

        $data->reset($bucket);
        $id = $data->getLastID($bucket);
        $this->assertEquals($id, 0);

        $data->store($bucket, 5);
        $id = $data->getLastID($bucket);
        $this->assertEquals($id, 5);
    }
}
