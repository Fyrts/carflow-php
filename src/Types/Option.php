<?php

namespace Carflow\Types;

use Carflow\XMLConverter;
use Carflow\XMLConvertable;

class Option extends XMLConvertable
{

	/**
	 * @param  SimpleXMLElement $XMLElement         Source XML element
	 * @param  bool             $describeProperties Properties in the XML are descriptive strings instead of foreign keys
	 */
	public function __construct(\SimpleXMLElement $XMLElement, bool $describeProperties)
	{
		$converter = new XMLConverter($XMLElement, $this);
		$converter->convertIntProperties('Id', 'CategoryID');
		$converter->convertStringProperties('Reference', 'Code', 'Translation');
		$converter->convertChildProperty('OptionCategory', $describeProperties);
	}

}
