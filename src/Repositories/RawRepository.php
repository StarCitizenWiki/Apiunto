<?php

declare(strict_types = 1);

namespace MediaWiki\Extension\Apiunto\Repositories;

/**
 * Star Citizen Comm-Link metadata
 */
class RawRepository extends AbstractRepository {

	public const API_ENDPOINT = 'api';

	/**
	 * @return string
	 */
	public function getRaw(): string {
		return $this->request();
	}
}
