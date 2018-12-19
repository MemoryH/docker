<?php

namespace Third\Doorman;

use Third\Doorman\Drivers\BasicDriver;
use Third\Doorman\Drivers\UuidDriver;
use Illuminate\Foundation\Application;
use Illuminate\Support\Manager;

/**
 * Class DoormanManager
 *
 * @package Third\Doorman
 * @method string code()
 */
class DoormanManager extends Manager
{
    public function __construct(Application $application)
    {
        parent::__construct($application);
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['doorman.driver'];
    }

    public function createBasicDriver()
    {
        return new BasicDriver;
    }

    public function createUuidDriver()
    {
        return new UuidDriver;
    }
}
