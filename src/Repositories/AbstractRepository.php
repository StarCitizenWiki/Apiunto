<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 * @file
 */

declare( strict_types=1 );

namespace MediaWiki\Extension\Apiunto\Repositories;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use MediaWiki\Extension\Apiunto\Scribunto_ApiuntoLuaLibrary;
use MediaWiki\MediaWikiServices;
use ObjectCache;

abstract class AbstractRepository {
	public const API_ENDPOINT = '';
	public const LOCALE = 'locale';

	/**
	 * @var Client
	 */
	protected $client;

	/**
	 * @var array|null
	 */
	protected $options;

	/**
	 * @var bool
	 */
	private $cacheWritten = false;

	/**
	 * AbstractRepository constructor.
	 *
	 * @param Client $client Request Client
	 * @param null|array $options Request options gets appended to the request url
	 */
	public function __construct( Client $client, $options = null ) {
		$this->client = $client;

		if ( is_array( $options ) ) {
			$this->options = $options;
		}
	}

	/**
	 * @param array $options
	 */
	public function setOptions( array $options ): void {
		$this->options = $options;
	}

	/**
	 * Check if the cache was written
	 *
	 * @return bool
	 */
	public function getCacheWritten(): bool {
		return $this->cacheWritten;
	}

	/**
	 * Perform the request
	 *
	 * @return string
	 */
	protected function request(): string {
		if ( ObjectCache::getLocalClusterInstance()->get( $this->makeCacheKey() ) !== false ) {
			return ObjectCache::getLocalClusterInstance()->get( $this->makeCacheKey() );
		}

		try {
			$url = sprintf(
				'%s/%s',
				static::API_ENDPOINT,
				$this->options[Scribunto_ApiuntoLuaLibrary::IDENTIFIER]
			);

			$response = $this->client->get(
				$url,
				[
					'query' => $this->options[Scribunto_ApiuntoLuaLibrary::QUERY_PARAMS]
				]
			);
		} catch ( ConnectException $e ) {
			return \GuzzleHttp\json_encode( [
				'error' => 500,
				'message' => 'Could not connect to Api',
			] );
		} catch ( ClientException $e ) {
			return $this->responseFromException( $e );
		}

		$content = (string)$response->getBody();

		$this->cacheWritten = $this->writeCache( $content );

		return (string)$response->getBody();
	}

	/**
	 * @param GuzzleException|null $exception
	 * @return mixed|string
	 */
	protected function responseFromException( ?GuzzleException $exception ) {
		if ( $exception === null || $exception->getResponse() === null ) {
			return 'Response is empty';
		}

		return $exception->getResponse()->getBody()->getContents();
	}

	/**
	 * Creates a key for caching
	 *
	 * @return string
	 */
	public function makeCacheKey(): string {
		return ObjectCache::getLocalClusterInstance()->makeGlobalKey(
			'apiunto',
			explode( '/', self::API_ENDPOINT )[1] ?? self::API_ENDPOINT,
			MediaWikiServices::getInstance()->getMainConfig()->get( 'ApiuntoApiVersion' ),
			...array_values( $this->options )
		);
	}

	/**
	 * Writes the response to cache
	 *
	 * @param $content
	 * @return bool
	 */
	protected function writeCache( $content ): bool {
		if ( MediaWikiServices::getInstance()->getMainConfig()->get( 'ApiuntoEnableCache' ) !== true ) {
			return false;
		}

		$expiries = MediaWikiServices::getInstance()->getMainConfig()->get( 'ApiuntoCacheTimes' );

		return ObjectCache::getLocalClusterInstance()->set(
			$this->makeCacheKey(),
			$content,
			$expiries[str_replace( 'api/', '', self::API_ENDPOINT )] ?? $expiries['Default'],
		);
	}

}
