<?php

declare( strict_types=1 );

namespace MediaWiki\Extension\Apiunto\Repositories;

/**
 * Star Citizen Manufacturer data
 */
class ManufacturerRepository extends AbstractRepository {
	/**
	 * Available includes on the api
	 */
	public const INCLUDES = [
		'ships',
		'vehicles',
	];

	public const API_ENDPOINT = 'api/manufacturers';

	/**
	 * Star Citizen Manufacturer
	 * https://docs.star-citizen.wiki/star_citizen_api.html#einzelner-hersteller
	 *
	 * @return string JSON data
	 */
	public function getManufacturer(): string {
		return $this->request();
	}
}
