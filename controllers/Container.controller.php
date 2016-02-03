<?php

/**
 * Container.controller.php
 * Description:
 *
 */

class Container
{
	static protected $shared = array();

	private $_logFile;
	private $_logLevel;

	public function __construct()
	{
		$properties = new BotsFeedUsProperties();
		$this->_logFile = $properties->getLogFile();
		$this->_logLevel = $properties->getLogLevel();
	}

	public function getLogger()
	{
		if (isset(self::$shared['logger'])) {
			return self::$shared['logger'];
		}

		$logger = new Logger($this->_logLevel, $this->_logFile);

		return self::$shared['logger'] = $logger;
	}

	public function getMongoDBConnect($collection)
	{
		if (isset(self::$shared['mongoDBConnect'])) {
			return self::$shared['mongoDBConnect'];
		}

		global $botsFeedUsMongoDBLogin;
		$mongoDBConnect = new MongoDBConnect($botsFeedUsMongoDBLogin['username'], $botsFeedUsMongoDBLogin['password'], $botsFeedUsMongoDBLogin['server'], $botsFeedUsMongoDBLogin['database'], $collection);

		return self::$shared['mongoDBConnect'] = $mongoDBConnect->getCollection();
	}

	public function getValidation()
	{
		if (isset(self::$shared['validation'])) {
			return self::$shared['validation'];
		}

		$validation = new BotsFeedUsFrameworkValidation($this->getLogger());

		return self::$shared['validation'] = $validation;
	}
}
