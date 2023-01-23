<?php

declare( strict_types=1 );

namespace MediaWiki\Extension\Apiunto\Repositories\Item;

use MediaWiki\Extension\Apiunto\Repositories\AbstractRepository;

final class CharArmorRepository extends AbstractRepository {
	/**
	 * Available includes on the api
	 */
	public const INCLUDES = [
		'attachments',
		'shops',
		'shops.items',
	];

	public const API_ENDPOINT = 'api/char/armor';

	/**
	 * @return string JSON data
	 */
	public function getCharArmor(): string {
		return $this->request();
	}
}
