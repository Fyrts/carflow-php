<?php

namespace Carflow\Types;

use Carflow\XMLConverter;
use Carflow\XMLConvertable;

class Vehicle extends XMLConvertable
{

	/**
	 * @param  SimpleXMLElement $XMLElement         Source XML element
	 * @param  bool             $describeProperties Properties in the XML are descriptive strings instead of foreign keys
	 */
	public function __construct(\SimpleXMLElement $XMLElement, bool $describeProperties)
	{
		$converter = new XMLConverter($XMLElement, $this);
		$converter->convertIntProperties('id','branch','state','mileage','numberOfPersons','numberOfGears','numberOfCylinders','modelYear','kw','fiscalHp','dinHp','numberOfDoors','warranty','numberOfMonths','internalStatus','CO2Emissions');
		if ($describeProperties) {
			$converter->convertStringProperties('state','brand','model','bodywork','fuelType','transmissionType','outerColor','innerColor','upholstery','carCategory');
		} else {
			$converter->convertIntProperties('state','brand','model','bodywork','fuelType','transmissionType','outerColor','innerColor','upholstery','carCategory');
		}
		$converter->convertFloatProperties('consumption','cylinderContent','massEmpty','massTMax','priceB2B','priceB2C');
		$converter->convertDateProperties('added','constructionDate','firstRegistration','creationDate');
		$converter->convertBoolProperties('isDeleted','isAccidentFree','isVatDeductable','hasDocIB','hasDocCoc','hasDocMaint','hasDoc705','hasCreditContract','hasInsuranceContract','hasMaintenanceContract');
		$converter->convertStringProperties('internalReference','chassisNumber','plateNumber','description','internalRemarks');
		$converter->convertHTMLProperties('remark');

		if (isset($XMLElement->branchgrouplabels->branchgrouplabel)) {
			$this->BranchGroupLabels = BranchGroupLabel::fromXMLChildren($XMLElement->branchgrouplabels->branchgrouplabel, $describeProperties);
		} else {
			$this->BranchGroupLabels = [];
		}

		$this->VehicleOptions = [];
		if (isset($XMLElement->vehicleOptions->option)) {
			foreach ($XMLElement->vehicleOptions->option as $option) {
				$this->VehicleOptions[] = $describeProperties ? strval($option) : intval($option);
			}
		}

		$this->VehicleImages = [];
		if (isset($XMLElement->vehicleImages->image)) {
			foreach ($XMLElement->vehicleImages->image as $image) {
				$this->VehicleImages[] = strval($image);
			}
		}
	}

}
