<?php

declare(strict_types=1);

namespace MediaWiki\Extension\Apiunto\Repositories\Ship;

use MediaWiki\Extension\Apiunto\Repositories\AbstractRepository;

final class PowerPlantRepository extends AbstractRepository
{
	/**
	 * Available includes on the api
	 */
	public const INCLUDES = [
		'shops',
		'shops.items',
	];

	public const API_ENDPOINT = 'api/ship-items/power-plants';

	/**
	 * Star Citizen Ship Power Plants
	 * https://docs.star-citizen.wiki/ship-items/power-plants.html
	 *
	 * @return string JSON data
	 */
	public function getPowerPlant(): string
	{
		return $this->request();
	}
}
