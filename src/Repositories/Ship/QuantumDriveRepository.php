<?php

declare( strict_types=1 );

namespace MediaWiki\Extension\Apiunto\Repositories\Ship;

use MediaWiki\Extension\Apiunto\Repositories\AbstractRepository;

final class QuantumDriveRepository extends AbstractRepository {
	/**
	 * Available includes on the api
	 */
	public const INCLUDES = [
		'shops',
		'shops.items',
	];

	public const API_ENDPOINT = 'api/ship-items/quantum-drives';

	/**
	 * Star Citizen Ship Quantum Drives
	 * https://docs.star-citizen.wiki/ship-items/quantum-drives.html
	 *
	 * @return string JSON data
	 */
	public function getQuantumDrive(): string {
		return $this->request();
	}
}
