<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

return [
    /*
     * The DataProvider implementation the Sequenatial database should use
     */
    'data_provider_class' => \danfekete\Sequential\DataProviders\SQLite::class,

    /*
     * The default increment amount
     */
    'increment_by' => 1,

    /**
     * Should the mutex be shared between all process
     * If it is set to TRUE only one ID is generated at a time regardless of the Bucket
     * otherwise ID generation for different buckets can run parallel
     */
    'shared_mutex' => false,
];