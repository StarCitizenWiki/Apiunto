<?php

declare(strict_types=1);

namespace MediaWiki\Extension\Apiunto\Repositories\Ship;

use MediaWiki\Extension\Apiunto\Repositories\AbstractRepository;

final class ShieldRepository extends AbstractRepository
{
	/**
	 * Available includes on the api
	 */
	public const INCLUDES = [
		'shops',
	];

	public const API_ENDPOINT = 'api/ship-items/shields';

	/**
	 * Star Citizen Ship Shields
	 * https://docs.star-citizen.wiki/ship-items/shields.html
	 *
	 * @return string JSON data
	 */
	public function getShield(): string
	{
		return $this->request();
	}
}
