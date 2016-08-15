<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

namespace danfekete\Sequential;


use Illuminate\Support\ServiceProvider;

class SequentialServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('sequential', function() {
            $config = $this->app->make('config');
            $dataProvider = new ($config->get('sequential.data_provider_class'));
            return new Sequential(
                $dataProvider,
                $config->get('sequential.shared_mutex'),
                $config->get('sequential.increment_by')
            );
        });
    }
}