<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

namespace danfekete\Sequential;


use danfekete\Sequential\Contracts\DataProvider;
use malkusch\lock\mutex\Mutex;
use malkusch\lock\mutex\PredisMutex;

class DistributedSequential extends Sequential
{
    /**
     * @var array
     */
    protected $redisClients;

    public function __construct(array $redisClients, DataProvider $dataProvider, $sharedMutex, $incr)
    {

        parent::__construct($dataProvider, $sharedMutex, $incr);
        $this->redisClients = $redisClients;
    }

    /**
     * Create a Mutex from the bucket
     * @param Bucket $bucket
     * @return Mutex
     */
    protected function getMutex(Bucket $bucket)
    {
        return new PredisMutex($this->redisClients, $this->getMutexKey($bucket));
    }

}