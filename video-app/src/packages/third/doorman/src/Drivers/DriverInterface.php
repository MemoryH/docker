<?php

namespace Third\Doorman\Drivers;

interface DriverInterface
{
    /**
     * Create an invite code.
     *
     * @return string
     */
    public function code(): string;
}
