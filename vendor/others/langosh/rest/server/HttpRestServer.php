<?php

namespace langosh\rest\server;

/**
 * @name HttpRestServer
 * @author Jan Svatoš <svatosja@gmail.com>
 */
abstract class HttpRestServer extends RestServer {
	
	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';
	const METHOD_PUT = 'PUT';
	const METHOD_DELETE = 'DELETE';
	
	private $headerStrings = array(
		200 => "OK",
		400 => "Bad Request",
		404 => "Not Found",
		405 => "Method Not Allowed",
		500 => "Internal Server Error"
	);
	
	
	/**
	 * @return string Method type
	 * @throws RestRpcServerException
	 */
	protected function getMethod() {
		$method = $this->getFilteredServerVar('REQUEST_METHOD');
		if( !$method ){
			throw RestServerException::unknownMethod($method);
		}
		return $method;
	}

	
	/**
	 * @return array
	 */
	protected function getResourcePath() {
		$uriExploded = explode('?', $this->getFilteredServerVar('REQUEST_URI'));
		$resourcePath = trim(current($uriExploded), '/');
		$resourcePathParts = explode('/', $resourcePath);

		return $resourcePathParts;
	}
	
	
	/**
	 * @return array
	 */
	protected function getData() {
		return \array_merge($_GET,$_POST);
	}

	
	/**
	 * @param \Exception $e
	 */
	protected function sendError(\Exception $e) {
		$code = $e->getCode() ?: 500;
		$this->sendResponseHeaders($code);
		echo $e->getMessage();
	}

	
	/**
	 * @param mixed $data
	 */
	protected function sendResponse($data) {
		$this->sendResponseHeaders();
		echo $data;
	}

	
	/**
	 * @param int $code HTTP status code
	 */
	protected function sendResponseHeaders($code = 200) {
		header( $this->getStatusHeader($code) );
		header('Content-Type: text/plain; charset=utf-8');
	}
	
	
	/**
	 * @param int $code HTTP status code
	 * @return string
	 */
	protected function getStatusHeader($code = 200) {
		$header = "HTTP/1.1 ".(int)$code;
		if( isset($this->headerStrings[$code]) ) {
			$header .= " " .$this->headerStrings[$code];
		}
		
		return $header;
	}
	
	
	/**
	 * @param string $variableName
	 * @return mixed
	 */
	private function getFilteredServerVar($variableName) {
		return filter_input(INPUT_SERVER, $variableName);
	}
	
}
