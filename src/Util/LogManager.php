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
			self::$logger = $logger;
		}
		return self::$logger;
	}
}
