<?php

declare(strict_types=1);

namespace MediaWiki\Extension\Apiunto\Repositories\Starmap;

use MediaWiki\Extension\Apiunto\Repositories\AbstractRepository;

class CelestialObjectRepository extends AbstractRepository {
	/**
	 * Available includes on the api
	 */
	public const INCLUDES = [
		'starsystem',
	];

	public const API_ENDPOINT = 'api/starmap/celestial-objects';

	/**
	 * Starmap data
	 *
	 * @return string
	 */
	public function getCelestialObject(): string {
		return $this->request();
	}
}
