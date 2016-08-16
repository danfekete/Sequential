<?php

/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */
class SequentialTest extends PHPUnit_Framework_TestCase
{
    protected $bucket;

    public function setUp()
    {
        $this->bucket = new \danfekete\Sequential\Bucket(1, 'danfekete');
    }

    public function testGenerate()
    {

    }
}
