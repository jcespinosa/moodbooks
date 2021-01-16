<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

class CreateCustomLogger {

    /**
     * Create a custom Monolog instance.
     *
     * @param    array    $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $filename = storage_path('logs/laravel-'.php_sapi_name().'.log');
        $max_files = config('logging.max_files');
        $level = config('logging.level');
        $handler = new RotatingFileHandler($filename, $max_files, $level);

        $name = config('app.name');
        $logger = new Logger($name);
        $logger->pushHandler($handler);
        return $logger;
    }
}
