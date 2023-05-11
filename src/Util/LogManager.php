<?php

namespace Pivvenit\FactuurSturen\Util;

use InvalidArgumentException;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LogManager
{
	private function __construct() {
		// Static class
	}

	private static LoggerInterface $logger;
	public static function getLogger() : LoggerInterface {
		if (!isset(self::$logger)) {
			// Initialize monolog
			$logger = new Logger('factuursturen-integration');
			// By default log to the PHP stderr
			$handlers = [new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, Level::Warning)];
			// Let other plugins add additional handlers
			$handlers = apply_filters('fsi_log_handlers', $handlers);
			if (!is_array($handlers)) {
				throw new InvalidArgumentException('fsi_log_handlers filter must return an array of Monolog\Handler\HandlerInterface');
			}
			foreach ($handlers as $handler) {
				$logger->pushHandler($handler);
			}

			$processors = [];
			// Let other plugins add additional processors
			$processors = apply_filters('fsi_log_processors', $processors);
			if (!is_array($processors)) {
				throw new InvalidArgumentException('fsi_log_processors filter must return an array of Monolog\Processor\ProcessorInterface');
			}

			foreach ($processors as $processor) {
				$logger->pushProcessor($processor);
			}

			// Allow other plugins to modify the logger
			$logger = apply_filters('fsi_log_configure', $logger);
			if (!($logger instanceof LoggerInterface)) {
				throw new InvalidArgumentException('fsi_log_configure filter must return an instance of Psr\Log\LoggerInterface');
			}
			self::$logger = $logger;
		}
		return self::$logger;
	}
}
