<?php

namespace Carflow;

class HTTPClient
{
	/**
	 * Instance of Guzzle Client
	 * @var GuzzleHttp\Client
	 */
	protected $GuzzleClient;

	/**
	 * Language as defined in /Carflow/Language
	 * @var int
	 */
	public $language;

	/**
	 * @param  int  $language   Language as defined in /Carflow/Language
	 */
	public function __construct(int $language)
	{
		$this->GuzzleClient = new \GuzzleHttp\Client(['base_uri' => 'http://cfmpublic.cloudapp.net/Exports/']);
		$this->language = $language;
	}

	/**
	 * Execute a simplified API request and return the result as an array of objects
	 *
	 * @method get
	 * @param  string $name       Tag name of the children in the XML
	 * @param  string $method     API method to call. Defaults to "Get{name}s".
	 * @param  string $classname  Name of the class that will be used to instantiate results. Defaults to the tag name.
	 * @return object[]
	 */
	protected function get(string $name, $method = null, $classname = null): array
	{
		if (is_null($method)) $method = "Get{$name}s";
		if (is_null($classname)) $classname = $name;

		$response = $this->makeRequest($method);
		if (!isset($response->$name)) return [];

		$classname = __NAMESPACE__."\\Types\\{$classname}";
		return $classname::fromXMLChildren($response->$name, false);
	}

	/**
	 * Execute an API request and return the resulting XML
	 *
	 * @method makeRequest
	 * @param  string      $method API method to call
	 * @param  array       $args   Associative array of query string arguments. Defaults to none.
	 * @return SimpleXMLElement
	 */
	protected function makeRequest(string $method, array $args = []): \SimpleXMLElement
	{
		$args['cultureId'] = $this->language;

		$responseBody = $this->GuzzleClient->get($method, [
			'query' => $args
		])->getBody()->getContents();

		$responseXML = simplexml_load_string($responseBody);
		if ($responseXML === false) {
			throw new ResponseException("Carflow server did not return valid XML.");
		}

		return $responseXML;
	}

}
