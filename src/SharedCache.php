<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

namespace danfekete\Sequential;


class SharedCache
{
    const MEMORY_SIZE = 65536; // 64kb

    const DATA_SIZE = 32; // 32 byte

    protected $shid;

    public function __construct()
    {
        $this->shid = shmop_open(ftok(__FILE__, 'a'), 'c', 0644, static::MEMORY_SIZE);
    }

    /**
     * Pack the data to store in the array
     * @param $key
     * @param $data
     * @return string
     */
    protected function pack($key, $data)
    {
        $len = strlen($key);
        $data = intval($data); // must be int, 64-bit
        return pack("CA23Q", $len, $key, $data);
    }

    /**
     * Read the data from binary format
     * @param $packed
     * @return array
     */
    protected function unpack($packed)
    {
        $unpacked = unpack('Clen/A23key/Qvalue', $packed);
        return [
            'key' => substr($unpacked['key'], 0, $unpacked['len']),
            'value' => $unpacked['value']
        ];
    }

    /**
     * Calculate the hash value of the key
     * @param $key
     * @return int
     */
    protected function hash($key)
    {
        $len = strlen($key);
        $checksum = 0;
        $numBuckets = static::MEMORY_SIZE / static::DATA_SIZE;
        for ($i = 0; $i < $len; $i++) {
            $checksum = ($checksum + ord($key[$i])) % $numBuckets;
        }

        return $checksum;
    }

    /**
     * Get the memory offset for the key
     * @param $key
     * @return int
     */
    protected function offset($key)
    {
        return $this->hash($key) * static::DATA_SIZE;
    }

    /**
     * Read the data found at key
     * @param $key
     * @return integer
     */
    public function read($key)
    {
        $bin = shmop_read($this->shid, $this->offset($key), static::DATA_SIZE);
        $data = $this->unpack($bin);
        if(strcmp($data['key'], $key) != 0) return -1;
        return intval($data['value']);
    }

    /**
     * Write data
     * @param $key
     * @param $value
     */
    public function write($key, $value)
    {
        $bin = $this->pack($key, $value);
        shmop_write($this->shid, $bin, $this->offset($key));
    }
}