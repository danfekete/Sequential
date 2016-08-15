<?php
use danfekete\Sequential\Bucket;

/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

class BucketTest extends \PHPUnit_Framework_TestCase
{

    public function testGetData()
    {
        $bucket = new Bucket(1, 'a', 2);
        $this->assertEquals([1, 'a', 2], $bucket->getData());
    }

    public function testKey()
    {
        $bucket = new Bucket(1, 'a', 2);
        $this->assertEquals('1-a-2', $bucket->key());
    }


}
