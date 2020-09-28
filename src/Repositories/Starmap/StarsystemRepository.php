<?php

declare(strict_types=1);

namespace MediaWiki\Extension\Apiunto\Repositories\Starmap;

use MediaWiki\Extension\Apiunto\Repositories\AbstractRepository;

class StarsystemRepository extends AbstractRepository {
	/**
	 * Available includes on the api
	 */
	public const INCLUDES = [
		'jumppoint_entries',
		'jumppoint_exits',
		'celestial_objects',
	];

	public const API_ENDPOINT = 'api/starmap/starsystems';

	/**
	 * Starmap data
	 *
	 * @return string
	 */
	public function getStarmap(): string {
		return $this->request();
	}
}
