<?php declare( strict_types=1 );

namespace MediaWiki\Extension\Apiunto;

use GuzzleHttp\Client;
use MediaWiki\Extension\Apiunto\Repositories\CommLinkRepository;
use MediaWiki\Extension\Apiunto\Repositories\ManufacturerRepository;
use MediaWiki\Extension\Apiunto\Repositories\Vehicle\GroundVehicleRepository;
use MediaWiki\Extension\Apiunto\Repositories\Vehicle\ShipRepository;
use Scribunto_LuaEngine;

class Scribunto_ApiuntoLuaLibrary extends \Scribunto_LuaLibraryBase {

	const QUERY = 'query';
	const LOCALE = 'locale';

	/**
	 * @var Client
	 */
	private static $client;

	public function __construct( Scribunto_LuaEngine $engine ) {
		parent::__construct( $engine );

		if ( static::$client === null ) {
			$this->initGuzzleClient();
		}
	}

	private function initGuzzleClient() {
		global $wgApiuntoUrl, $wgApiuntoTimeout, $wgApiuntoKey, $wgApiuntoVersion;

		$headers = [
			'Accept' => "application/x.StarCitizenWikiApi.{$wgApiuntoVersion}+json",
		];

		if ( null !== $wgApiuntoKey ) {
			$headers['Authorization'] = "Bearer {$wgApiuntoKey}";
		}

		static::$client = new Client( [
			'base_uri' => $wgApiuntoUrl,
			'timeout' => $wgApiuntoTimeout,
			'headers' => $headers,
		] );
	}

	public function register() {
		$lib = [
			'get_ship' => [ $this, 'getShip' ],
			'get_ground_vehicle' => [ $this, 'getGroundVehicle' ],
			'get_manufacturer' => [ $this, 'getManufacturer' ],
			'get_comm_link_metadata' => [ $this, 'getCommLinkMetadata' ],
		];

		return $this->getEngine()->registerInterface( __DIR__ . '/mw.ext.Apiunto.lua', $lib, [] );
	}

	public function getShip() {
		$params = func_get_args();

		$repository = new ShipRepository( static::$client, [
			self::QUERY => $params[0],
			self::LOCALE => $params[1],
		] );

		return [ $repository->getVehicle() ];
	}

	public function getGroundVehicle() {
		$params = func_get_args();

		$repository = new GroundVehicleRepository( static::$client, [
			self::QUERY => $params[0],
			self::LOCALE => $params[1],
		] );

		return [ $repository->getVehicle() ];
	}

	public function getManufacturer() {
		$params = func_get_args();

		$repository = new ManufacturerRepository( static::$client, [
			self::QUERY => $params[0],
			self::LOCALE => $params[1],
		] );

		return [ $repository->getManufacturer() ];
	}

	public function getCommLinkMetadata() {
		$params = func_get_args();

		$repository = new CommLinkRepository( static::$client, [
			self::QUERY => $params[0]
		] );

		return [ $repository->getCommLinkMetadata() ];
	}

}
