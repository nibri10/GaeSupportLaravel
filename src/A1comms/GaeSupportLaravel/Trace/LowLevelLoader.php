<?php

namespace A1comms\GaeSupportLaravel\Trace;

/**
 * Class to return the low level trace modules to load.
 */
class LowLevelLoader implements LowLevelLoaderInterface
{
    /**
     * Static method to get the list of trace modules to load.

     */
    public static function getList()
    {
        $loaders = [
            // Trace our basic functions...
            \OpenCensus\Trace\Integrations\Mysql::class,
            \OpenCensus\Trace\Integrations\PDO::class,
            \OpenCensus\Trace\Integrations\Memcached::class,
            \A1comms\GaeSupportLaravel\Trace\Integration\LowLevel\Grpc::class,
            \A1comms\GaeSupportLaravel\Trace\Integration\Guzzle\TraceProvider::class,
        ];

        if (is_lumen()) {
            $loaders = array_merge($loaders, [
                // Lumen classes are different than for Laravel,
                // trace is separately.
                \A1comms\GaeSupportLaravel\Trace\Integration\LowLevel\Lumen::class,
            ]);
        } else {
            $loaders = array_merge($loaders, [
                // OpenCensus provides a basic Laravel trace adapter,
                // which covered Eloquent and view compilation.
                \OpenCensus\Trace\Integrations\Laravel::class,
                // Also load our own extended Laravel trace set.
                \A1comms\GaeSupportLaravel\Trace\Integration\LowLevel\LaravelExtended::class,
            ]);
        }

        return $loaders;
    }
}