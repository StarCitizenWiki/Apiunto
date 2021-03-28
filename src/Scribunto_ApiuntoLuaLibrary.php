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

namespace MediaWiki\Extension\Apiunto;

use ConfigException;
use GuzzleHttp\Client;
use MediaWiki\Extension\Apiunto\Repositories\AbstractRepository;
use MediaWiki\Extension\Apiunto\Repositories\CommLinkRepository;
use MediaWiki\Extension\Apiunto\Repositories\GalactapediaRepository;
use MediaWiki\Extension\Apiunto\Repositories\ManufacturerRepository;
use MediaWiki\Extension\Apiunto\Repositories\RawRepository;
use MediaWiki\Extension\Apiunto\Repositories\Starmap\CelestialObjectRepository;
use MediaWiki\Extension\Apiunto\Repositories\Starmap\StarsystemRepository;
use MediaWiki\Extension\Apiunto\Repositories\Vehicle\GroundVehicleRepository;
use MediaWiki\Extension\Apiunto\Repositories\Vehicle\ShipRepository;
use MediaWiki\MediaWikiServices;
use Scribunto_LuaEngine;
use Scribunto_LuaLibraryBase;

/**
 * Methods callable by LUA
 */
class Scribunto_ApiuntoLuaLibrary extends Scribunto_LuaLibraryBase {

	/**
	 * Page identifier like ship name oder comm-link id
	 */
	public const IDENTIFIER = 'identifier';

	/**
	 * Query parameters
	 */
	public const QUERY_PARAMS = 'query';

	/**
	 * @var Client
	 */
	private static $client;

	private $availableIncludes;

	/**
	 * Initializes the guzzle client
	 *
	 * @param Scribunto_LuaEngine $engine
	 */
	public function __construct( Scribunto_LuaEngine $engine ) {
		parent::__construct( $engine );

		if ( static::$client === null ) {
			$this->initGuzzleClient();
		}
	}

	/**
	 * Initializes the guzzle client
	 * Adds the bearer token if set in the config
	 *
	 * @return void
	 */
	private function initGuzzleClient(): void {
		$apiKey = $this->getConfigValue( 'ApiuntoKey' );
		$version = $this->getConfigValue( 'ApiuntoVersion', 'v1' );

		$headers = [
			'Accept' => "application/x.StarCitizenWikiApi.{$version}+json",
		];

		if ( null !== $apiKey ) {
			$headers['Authorization'] = "Bearer {$apiKey}";
		}

		static::$client = new Client( [
			'base_uri' => $this->getConfigValue( 'ApiuntoUrl' ),
			'timeout' => $this->getConfigValue( 'ApiuntoTimeout', 5 ),
			'headers' => $headers,
		] );
	}

	/**
	 * Registers the callable lua methods
	 *
	 * @return array
	 */
	public function register(): array {
		$lib = [
			'get_ship' => [ $this, 'getShip' ],
			'get_ground_vehicle' => [ $this, 'getGroundVehicle' ],
			'get_manufacturer' => [ $this, 'getManufacturer' ],
			'get_comm_link_metadata' => [ $this, 'getCommLinkMetadata' ],
			'get_starsystem' => [ $this, 'getStarsystem' ],
			'get_celestial_object' => [ $this, 'getCelestialObject' ],
			'get_galactapedia' => [ $this, 'getGalactapedia' ],
			'get_raw' => [ $this, 'getRaw' ],
		];

		return $this->getEngine()->registerInterface( __DIR__ . '/mw.ext.Apiunto.lua', $lib, [] );
	}

	/**
	 * Requests a single ship
	 *
	 * @return array Shipdata
	 */
	public function getShip(): array {
		$params = func_get_args();

		$this->availableIncludes = ShipRepository::INCLUDES;

		$repository = new ShipRepository( static::$client, [
			self::IDENTIFIER => $params[0],
			self::QUERY_PARAMS => $this->processArgs( $params[1] ),
		] );

		$response = $repository->getVehicle();
		$this->writeCachePropertyKey( $repository );

		return [ $response ];
	}

	/**
	 * Requests a single ground vehicle
	 *
	 * @return array Ground vehicle data
	 */
	public function getGroundVehicle(): array {
		$params = func_get_args();

		$this->availableIncludes = ShipRepository::INCLUDES;

		$repository = new GroundVehicleRepository( static::$client, [
			self::IDENTIFIER => $params[0],
			self::QUERY_PARAMS => $this->processArgs( $params[1] ),
		] );

		$response = $repository->getVehicle();
		$this->writeCachePropertyKey( $repository );

		return [ $response ];
	}

	/**
	 * Requests a single manufacturer
	 *
	 * @return array Manufacturer data
	 */
	public function getManufacturer(): array {
		$params = func_get_args();

		$this->availableIncludes = ManufacturerRepository::INCLUDES;

		$repository = new ManufacturerRepository( static::$client, [
			self::IDENTIFIER => $params[0],
			self::QUERY_PARAMS => $this->processArgs( $params[1] ),
		] );

		$response = $repository->getManufacturer();
		$this->writeCachePropertyKey( $repository );

		return [ $response ];
	}

	/**
	 * Requests metadata about a single comm link
	 *
	 * @return array Comm Link Metadata
	 */
	public function getCommLinkMetadata(): array {
		$params = func_get_args();

		$this->availableIncludes = CommLinkRepository::INCLUDES;

		$repository = new CommLinkRepository( static::$client, [
			self::IDENTIFIER => $params[0],
			self::QUERY_PARAMS => $this->processArgs( $params[1] ),
		] );

		$response = $repository->getCommLinkMetadata();
		$this->writeCachePropertyKey( $repository );

		return [ $response ];
	}

	/**
	 * Requests metadata for a starsystem
	 *
	 * @return array Starsystem Data
	 */
	public function getStarsystem(): array {
		$params = func_get_args();

		$this->availableIncludes = StarsystemRepository::INCLUDES;

		$repository = new StarsystemRepository( static::$client, [
			self::IDENTIFIER => $params[0],
			self::QUERY_PARAMS => $this->processArgs( $params[1] ),
		] );

		$response = $repository->getStarmap();
		$this->writeCachePropertyKey( $repository );

		return [ $response ];
	}

	/**
	 * Requests metadata for a celestial object
	 *
	 * @return array Celestial object data
	 */
	public function getCelestialObject(): array {
		$params = func_get_args();

		$this->availableIncludes = CelestialObjectRepository::INCLUDES;

		$repository = new CelestialObjectRepository( static::$client, [
			self::IDENTIFIER => $params[0],
			self::QUERY_PARAMS => $this->processArgs( $params[1] ),
		] );

		$response = $repository->getCelestialObject();
		$this->writeCachePropertyKey( $repository );

		return [ $response ];
	}

	/**
	 * Requests metadata for a galactapedia article
	 *
	 * @return array Galactapedia Article data
	 */
	public function getGalactapedia(): array {
		$params = func_get_args();

		$this->availableIncludes = GalactapediaRepository::INCLUDES;

		$repository = new GalactapediaRepository( static::$client, [
			self::IDENTIFIER => $params[0],
			self::QUERY_PARAMS => $this->processArgs( $params[1] ),
		] );

		$response = $repository->getGalactapediaData();
		$this->writeCachePropertyKey( $repository );

		return [ $response ];
	}

	/**
	 * Raw request
	 *
	 * Identifier is the complete uri excluding 'api'
	 * E.g.: starmap/starsystems
	 *
	 * @return array
	 */
	public function getRaw(): array {
		$params = func_get_args();

		$this->availableIncludes = $params[1]['include'] ?? [];

		$repository = new RawRepository( static::$client, [
			self::IDENTIFIER => $params[0],
			self::QUERY_PARAMS => $this->processArgs( $params[1] ),
		] );

		$response = $repository->getRaw();
		$this->writeCachePropertyKey( $repository );

		return [ $response ];
	}

	/**
	 * Processes the method arguments
	 * Returns an array used by http_build_query
	 *
	 * @param array $arguments Method arguments
	 * @return array HTTP Query data
	 */
	private function processArgs( array $arguments ): array {
		$data = [
			'limit' => $arguments['limit'] ?? '',
			'page' => $arguments['page'] ?? '',
			'locale' => $this->processLocale( $arguments ),
			'include' => $this->processIncludes( $arguments ),
		];

		return array_filter( $data, static function ( $value ) {
			return !empty( $value );
		} );
	}

	/**
	 * @param array $arguments Method arguments
	 * @return string Locale string
	 */
	private function processLocale( array $arguments ): string {
		if ( !isset( $arguments['locale'] ) ) {
			return $this->getConfigValue( 'ApiuntoDefaultLocale', null ) ?? '';
		}

		$arguments = $arguments['locale'];

		if ( !is_array( $arguments ) ) {
			$arguments = [ $arguments ];
		}

		return implode( ',', $arguments );
	}

	/**
	 * Returns a string with all invalid includes removed
	 *
	 * @param array $arguments Method arguments
	 * @return string
	 */
	private function processIncludes( array $arguments ): string {
		if ( !isset( $arguments['include'] ) ) {
			return '';
		}

		$arguments = $arguments['include'];

		if ( !is_array( $arguments ) ) {
			$arguments = [ $arguments ];
		}

		$validIncludes = [];

		foreach ( $arguments as $include ) {
			foreach ( $this->availableIncludes as $availableInclude ) {
				if ( strpos( $include, $availableInclude ) !== false ) {
					$validIncludes[] = $include;
				}
			}
		}

		return implode( ',', $validIncludes );
	}

	/**
	 * Loads a config value for a given key from the main config
	 * Returns null on if an ConfigException was thrown
	 *
	 * @param string $key The config key
	 * @param null $default Default value to return
	 * @return mixed|null
	 */
	private function getConfigValue( string $key, $default = null ) {
		try {
			$value = MediaWikiServices::getInstance()->getMainConfig()->get( $key );
		} catch ( ConfigException $e ) {
			if ( $default === null ) {
				wfLogWarning( sprintf( 'Could not get config for "$wg%s". %s', $key,
					$e->getMessage() ) );
				$value = null;
			} else {
				$value = $default;
			}
		}

		return $value;
	}

	/**
	 * Write the cache key to the page props for purging
	 *
	 * @param AbstractRepository $repository
	 */
	private function writeCachePropertyKey( AbstractRepository $repository ): void {
		if ( $repository->getCacheWritten() ) {
			$this->getParser()->getOutput()->setProperty( 'apiuntocache', $repository->makeCacheKey() );
		}
	}
}
