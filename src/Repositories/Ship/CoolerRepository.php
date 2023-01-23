<?php

declare( strict_types=1 );

namespace MediaWiki\Extension\Apiunto\Repositories\Ship;

use MediaWiki\Extension\Apiunto\Repositories\AbstractRepository;

final class CoolerRepository extends AbstractRepository {
	/**
	 * Available includes on the api
	 */
	public const INCLUDES = [
		'shops',
		'shops.items',
	];

	public const API_ENDPOINT = 'api/ship-items/coolers';

	/**
	 * Star Citizen Ship Coolers
	 * https://docs.star-citizen.wiki/ship-items/coolers.html
	 *
	 * @return string JSON data
	 */
	public function getCooler(): string {
		return $this->request();
	}
}
