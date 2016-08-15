<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

namespace danfekete\Sequential\Contracts;


use danfekete\Sequential\Bucket;

interface DataProvider
{
    /**
     * Return the last integer ID for the given bucket
     * @return integer
     */
    public function getLastID(Bucket $bucket);

    /**
     * Store the given bucket's value in the dataprovider
     * @param Bucket $bucket
     * @param $value
     * @return mixed
     */
    public function store(Bucket $bucket, $value);

    /**
     * Reset a given bucket
     * @param Bucket $bucket
     * @return mixed
     */
    public function reset(Bucket $bucket);
}