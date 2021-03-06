<?php
namespace Carflow;

abstract class XMLConvertable
{

	/**
	 * Convert a SimpleXMLElement into a list of instances of the associated class
	 *
	 * @method fromXMLChildren
	 * @param  SimpleXMLElement $XMLChildrenElement XML element(s) to convert
	 * @param  bool             $describeProperties Properties in the XML are descriptive strings instead of foreign keys
	 * @return self[]
	 */
	public static function fromXMLChildren(\SimpleXMLElement $XMLChildrenElement, bool $describeProperties)
	{
		$classname = get_called_class();

		$result = [];
		foreach ($XMLChildrenElement as $childXML) {
			$result[] = new $classname($childXML, $describeProperties);
		}
		return $result;
	}

}
