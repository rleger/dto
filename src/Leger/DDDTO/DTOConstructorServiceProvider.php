<?php namespace Leger\dto;

use Illuminate\Support\ServiceProvider;

class DTOConstructorServiceProvider extends ServiceProvider {

        /**
         * Indicates if loading of the provider is deferred.
         *
         * @var bool
         */
        protected $defer = false;

        /**
         * Booting
         */
        public function boot()
        {
            $this->package('leger/dto');
        }

        /**
         * Register the commands
         *
         * @return void
         */
        public function register()
        {
        }

        /**
         * Get the services provided by the provider.
         *
         * @return array
         */
        public function provides()
        {
            return array();
        }

}
