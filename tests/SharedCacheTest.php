<?php

/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */
class SharedCacheTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \danfekete\Sequential\SharedCache
     */
    private $cache;

    public function setUp()
    {
        $this->cache = new \danfekete\Sequential\SharedCache();
    }

    public function testWrite()
    {
        $data = random_int(0, PHP_INT_MAX);
        $this->cache->write('phpunit', $data);
        $read = $this->cache->read('phpunit');
        $this->assertEquals($data, $read);
    }
}
