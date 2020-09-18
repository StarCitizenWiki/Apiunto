<?php declare(strict_types = 1);

namespace MediaWiki\Extension\Apiunto\Repositories;

/**
 * Star Citizen Comm-Link metadata
 */
class CommLinkRepository extends AbstractRepository {
	/**
	 * Available includes on the api
	 */
	public const INCLUDES = [
		'images',
		'links',
		'english',
		'german',
	];

	public const API_ENDPOINT = 'api/comm-links';

	/**
	 * Comm-Link Metadata
	 * https://docs.star-citizen.wiki/star_citizen_api.html#einzelner-comm-link
	 *
	 * @return string
	 */
	public function getCommLinkMetadata(): string {
		return $this->request();
	}
}
