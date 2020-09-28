<?php

declare( strict_types=1 );

namespace MediaWiki\Extension\Apiunto\Repositories\Vehicle;

use MediaWiki\Extension\Apiunto\Repositories\AbstractRepository;

/**
 * Base vehicle class
 */
abstract class AbstractVehicleRepository extends AbstractRepository {
	/**
	 * Available includes on the api
	 */
	public const INCLUDES = [
		'components',
	];

	/**
	 * Ship / Ground Vehicles
	 *
	 * @return string JSON data
	 * @see GroundVehicleRepository
	 *
	 * @see ShipRepository
	 */
	public function getVehicle(): string {
		return $this->request();
	}
}
