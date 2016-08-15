<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

namespace danfekete\Sequential;


class Bucket
{
    /**
     * @var array
     */
    private $data;

    /**
     * Bucket constructor.
     * @param array $data
     */
    public function __construct(...$data)
    {
        $this->data = $data;
    }

    /**
     * Return the bucket data
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Create a key based on the bucket data
     * @return string
     */
    public function key()
    {
        return implode('-', $this->data);
    }
}