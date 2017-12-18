<?php

namespace Carflow;

use NumberFormatter;
use DateTime;

class XMLConverter
{
	/**
	 * The XML element from which properties will be retreived
	 * @var \SimpleXMLElement
	 */
	protected $XMLElement;

	/**
	 * The object that will receive new properties
	 * @var object
	 */
	protected $target;

	/**
	 * @param  SimpleXMLElement $XMLElement The XML element from which properties will be retreived
	 * @param  object           $target     The object that will receive new properties
	 */
	public function __construct(\SimpleXMLElement $XMLElement, $target)
	{
		$this->XMLElement = $XMLElement;
		$this->target = $target;
	}

	/**
	* Retrieve properties from the XML element and convert them to int properties of the target object
	*
	* @param string $property,...
	*/
	public function convertIntProperties(string $property)
	{
		foreach (func_get_args() as &$arg) {
			$this->setProperty($arg, 'intval');
		}
	}

	/**
	* Retrieve properties from the XML element and convert them to float properties of the target object
	*
	* @param string $property,...
	*/
	public function convertFloatProperties(string $property)
	{
		foreach (func_get_args() as &$arg) {
			$this->setProperty($arg, function($float) {
				$formatter = new NumberFormatter('nl_BE', NumberFormatter::DECIMAL);
				return $formatter->parse($float);
			});
		}
	}

	/**
	* Retrieve properties from the XML element and convert them to DateTime properties of the target object
	*
	* @param string $property,...
	*/
	public function convertDateProperties(string $property)
	{
		foreach (func_get_args() as &$arg) {
			$this->setProperty($arg, function($date) {
				if (strpos($date, ':') === false) $date .= ' 0:00:00';
				return DateTime::createFromFormat('d/m/Y G:i:s', $date);
			});
		}
	}

	/**
	* Retrieve properties from the XML element and convert them to bool properties of the target object
	*
	* @param string $property,...
	*/
	public function convertBoolProperties(string $property)
	{
		foreach (func_get_args() as &$arg) {
			$this->setProperty($arg, function($bool) {
				return strtolower($bool) === 'true';
			});
		}
	}

	/**
	* Retrieve properties from the XML element and convert them to string properties of the target object
	*
	* @param string $property,...
	*/
	public function convertStringProperties(string $property)
	{
		foreach (func_get_args() as &$arg) {
			$this->setProperty($arg, 'strval');
		}
	}

	/**
	* Retrieve properties from the XML element and convert them to string properties of the target object, decoding HTML entities
	*
	* @param string $property,...
	*/
	public function convertHTMLProperties(string $property)
	{
		foreach (func_get_args() as &$arg) {
			$this->setProperty($arg, function($string) {
				return html_entity_decode($string ,ENT_QUOTES);
			});
		}
	}

	/**
	* Retrieve a child from the XML element and convert it to a valid object, storing it as a property of the target object
	*
	* @param string $property
	* @param string $describeProperties Whether the properties are descriptive strings, or foreign keys
	* @param string $property
	*/
	public function convertChildProperty(string $property, bool $describeProperties, $typename = null)
	{
		if (is_null($typename)) $typename = $property;
		$typename = __NAMESPACE__."\\Types\\{$typename}";

		if (isset($this->XMLElement->$property)) {
			$this->target->$property = $typename::fromXMLChildren($this->XMLElement->$property, $describeProperties);
		} else {
			$this->target->$property = [];
		}
	}

	/**
	* Get a property from the XML element, process it using a callback function, and set it on the target
	*
	* @param string $property
	* @param callable $callable
	*/
	protected function setProperty(string $property, $callable)
	{
		$targetProperty = ucfirst($property);
		if (isset($this->XMLElement->$property) && strlen($this->XMLElement->$property) > 0) {
			$this->target->$targetProperty = $callable($this->XMLElement->$property);
		} else {
			$this->target->$targetProperty = null;
		}
	}

}
