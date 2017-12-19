<?php

namespace Carflow;

class Client extends HTTPClient
{
	/**
	 * Get all vehicles associated with a branch
	 *
	 * @method getBranchVehicles
	 * @param  int               $branchID
	 * @param  boolean           $describeProperties Retrieve properties as descriptive strings instead of foreign keys
	 * @param  boolean           $includeDeleted     Include vehicles that have been deleted. Defaults to false.
	 * @param  integer           $since              Unix timestamp of the last update. Defaults to none.
	 * @return Types\Vehicle[]
	 */
	public function getBranchVehicles(int $branchID, bool $describeProperties = false, bool $includeDeleted = false, int $since = 0): array
	{
		$response = $this->makeRequest('BranchVehicles', [
			'branchId' => $branchID,
			'useIds' => $describeProperties ? 'false' : 'true',
			'includeDeletedVehicles' => $includeDeleted ? 'true' : 'false',
			'since' => date('Y-m-d', $since)
		]);
		if (!isset($response->vehicle)) return [];

		return Types\Vehicle::fromXMLChildren($response->vehicle, $describeProperties);
	}

	/**
	 * Get all possible car brands
	 *
	 * @method getBrands
	 * @return Types\Brand[]
	 */
	public function getBrands(): array
	{
		return $this->get('Brand');
	}

	/**
	 * Get all possible car models
	 *
	 * @method getModels
	 * @return Types\Model[]
	 */
	public function getModels(): array
	{
		return $this->get('Model');
	}

	/**
	 * Get all possible bodywork types
	 *
	 * @method getBodyworks
	 * @return Type\Bodywork[]
	 */
	public function getBodyworks(): array
	{
		return $this->get('Bodywork');
	}

	/**
	 * Get all possible fuel types
	 *
	 * @method getFuelTypes
	 * @return Type\FuelType[]
	 */
	public function getFuelTypes(): array
	{
		return $this->get('FuelType');
	}

	/**
	 * Get all possible transmission types
	 *
	 * @method getTransmissions
	 * @return Types\Transmission[]
	 */
	public function getTransmissions(): array
	{
		return $this->get('Transmission');
	}

	/**
	 * Get all possible interior colors
	 *
	 * @method getInnerColors
	 * @return Types\InnerColor[]
	 */
	public function getInnerColors(): array
	{
		return $this->get('InnerColor');
	}

	/**
	 * Get all possible exterior colors
	 *
	 * @method getOuterColors
	 * @return Types\OuterColor[]
	 */
	public function getOuterColors(): array
	{
		return $this->get('OuterColor');
	}

	/**
	 * Get all possible upholstery types
	 *
	 * @method getUpholsteries
	 * @return Types\Upholstery[]
	 */
	public function getUpholsteries(): array
	{
		return $this->get('Upholstery','GetUpholsteries');
	}

	/**
	 * Get all possible option categories
	 *
	 * @method getOptionCategories
	 * @return Types\OptionCategory[]
	 */
	public function getOptionCategories(): array
	{
		return $this->get('OptionCategory','GetOptionCategories');
	}

	/**
	 * Get all possible options
	 *
	 * @method getOptions
	 * @return Types\Option[]
	 */
	public function getOptions(): array
	{
		return $this->get('Option');
	}

}
