<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

namespace danfekete\Sequential;


use Illuminate\Support\Facades\Facade;

class SequentialFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sequential';
    }

}