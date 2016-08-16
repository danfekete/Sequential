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
use malkusch\lock\mutex\SemaphoreMutex;

class Sequential
{
    /**
     * @var DataProvider
     */
    protected $dataProvider;
    /**
     * @var bool
     */
    protected $sharedMutex;
    /**
     * @var int
     */
    protected $incr;

    /**
     * Sequential constructor.
     * @param DataProvider $dataProvider
     * @param bool $sharedMutex Mutex should be shared with the other requests
     * @param int $incr Increment by this amount
     */
    public function __construct(DataProvider $dataProvider, $sharedMutex = false, $incr=1)
    {
        $this->dataProvider = $dataProvider;
        $this->sharedMutex = $sharedMutex;
        $this->incr = $incr;
    }

    /**
     * Get the key name for the mutex
     * @param Bucket $bucket
     * @return string
     */
    protected function getMutexKey(Bucket $bucket)
    {
        $semName = $this->sharedMutex ? 'seq' : $bucket->key();
        return $semName;
    }

    /**
     * Create a Mutex from the bucket
     * @param Bucket $bucket
     * @return Mutex
     */
    protected function getMutex(Bucket $bucket)
    {
        $semName = $this->getMutexKey($bucket);
        return new SemaphoreMutex(sem_get(ftok(__FILE__, $semName)));
    }

    /**
     * Generate the next ID in the Bucket
     * @param Bucket $bucket
     * @param null|callable $callback
     * @return int
     */
    public function generate(Bucket $bucket, $callback = null)
    {
        return $this->getMutex($bucket)->synchronized(function() use ($bucket, $callback) {
            $lastID = $this->dataProvider->getLastID($bucket);
            $lastID += $this->incr;

            // store the new ID
            $this->dataProvider->store($bucket, $lastID);
            // if we have a callable set, run it now, while still in the synchronized environment
            if($callback) $callback($lastID);

            // return the new ID for use
            return $lastID;
        });
    }
}