<?php

/**
 * MobileAPIValidation.class.php
 * Description:
 *
 */

class BotsFeedUsFrameworkValidation
{
	private $_validate;
	private $_logger;
	private $_errorCode = '';
	private $_errors = array();
	private $_friendlyError = '';
	private $_errorCount = 0;

	public function __construct($logger)
	{
		//setup for log entries
		$this->_logger = $logger;

		$this->_validate = new FrameworkValidation();
	}

	public function sanitizeWhoOrWhat($entity) {
		return $this->_validate->sanitizeTextWithSpace($entity);
	}

	public function validateWhoOrWhat($requestUri)
	{
		$this->validateTextWithSpace($requestUri[0], FALSE);
		$this->validateTextWithSpace($requestUri[1], FALSE);
	}

	private function validateTextWithSpace($entity, $required) {
		if (isset($entity) && !empty($entity)) {
			$this->_logger->debug('Checking text with space: ' . $entity);
			$returnError = $this->_validate->validateTextWithSpace($entity);
			if (!empty($returnError)) $this->reportVariableErrors('invalid', 'text with space', $returnError);
		} else if ($required === TRUE) {
			$this->reportVariableErrors('missing', 'text with space', '');
		}
	}

	private function reportVariableErrors($type, $variable, $returnError) {
		if ($type === 'invalid') {
			$this->_errorCode = 'invalidParameter';
			$this->_errors[] = $returnError . ' value for ' . $variable . ')';
			$this->_friendlyError = 'Invalid value or format for one of the submitted parameters.';
			$this->_errorCount++;
		} else if ($type === 'missing') {
			$this->_errorCode = 'missingParameter';
			$this->_errors[] = 'Required parameter ' . $variable . ' is missing from request.';
			$this->_friendlyError = 'A required parameter is missing from this request.';
			$this->_errorCount++;
		}
	}

	public function getErrorCode() {
		return $this->_errorCode;
	}

	public function getErrors() {
		return $this->_errors;
	}

	public function getFriendlyError() {
		return $this->_friendlyError;
	}

	public function getErrorCount() {
		return $this->_errorCount;
	}
}
