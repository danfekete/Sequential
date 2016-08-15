<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

namespace danfekete\Sequential\DataProviders;


use danfekete\Sequential\Bucket;
use danfekete\Sequential\Contracts\DataProvider;

class SQLite implements DataProvider
{
    protected $db;
    /**
     * @var string
     */
    private $dbName;

    /**
     * SQLite constructor.
     * @param string $dbName
     */
    public function __construct($dbName='sequential.db')
    {
        $this->dbName = $dbName;
        $exists = is_file($dbName);
        $this->db = new \PDO("sqlite:{$this->dbName}");
        if(!$exists) {
            // DB was just created
            $this->db->exec('CREATE TABLE IF NOT EXISTS seq (id INTEGER, bucket STRING (128) PRIMARY KEY)');
        }
    }

    /**
     * Return the last integer ID for the given bucket
     * @return integer
     */
    public function getLastID(Bucket $bucket)
    {
        $stmt = $this->db->prepare('SELECT id FROM seq WHERE bucket=?');
        $stmt->execute([$bucket->key()]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if(!$data || is_nan($data['id'])) {
            // insert into datatable
            $insertStmt = $this->db->prepare('INSERT INTO seq(id, bucket) VALUES(0, ?)');
            $insertStmt->execute([$bucket->key()]);
            return 0;
        }

        return intval($data['id']);
    }


    /**
     * Store the given bucket's value in the dataprovider
     * @param Bucket $bucket
     * @param $value
     * @return mixed
     */
    public function store(Bucket $bucket, $value)
    {
        $stmt = $this->db->prepare('UPDATE seq SET id=? WHERE bucket=?');
        $stmt->execute([$value, $bucket->key()]);
    }

    /**
     * Reset a given bucket
     * @param Bucket $bucket
     * @return mixed
     */
    public function reset(Bucket $bucket)
    {
        $this->store($bucket, 0);
    }
}