<?php declare( strict_types=1 );

namespace MediaWiki\Extension\Apiunto;

use ConfigException;
use GuzzleHttp\Client;
use MediaWiki\Extension\Apiunto\Repositories\CommLinkRepository;
use MediaWiki\Extension\Apiunto\Repositories\ManufacturerRepository;
use MediaWiki\Extension\Apiunto\Repositories\Vehicle\GroundVehicleRepository;
use MediaWiki\Extension\Apiunto\Repositories\Vehicle\ShipRepository;
use MediaWiki\MediaWikiServices;
use Scribunto_LuaEngine;

/**
 * Methods callable by LUA
 */
class Scribunto_ApiuntoLuaLibrary extends \Scribunto_LuaLibraryBase
{

    const QUERY = 'query';
    const LOCALE = 'locale';

    /**
     * @var Client
     */
    private static $client;

    /**
     * Initializes the guzzle client
     *
     * @param Scribunto_LuaEngine $engine
     */
    public function __construct( Scribunto_LuaEngine $engine )
    {
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
    private function initGuzzleClient()
    {
        $apiKey = $this->getConfigValue( 'ApiuntoKey' );
        $version = $this->getConfigValue( 'ApiuntoVersion' );

        $headers = [
        'Accept' => "application/x.StarCitizenWikiApi.{$version}+json",
        ];

        if ( null !== $apiKey ) {
            $headers[ 'Authorization' ] = "Bearer {$apiKey}";
        }

        static::$client = new Client(
            [
            'base_uri' => $this->getConfigValue( 'ApiuntoUrl' ),
            'timeout' => $this->getConfigValue( 'ApiuntoTimeout' ),
            'headers' => $headers,
            ] 
        );
    }

    /**
     * Registers the callable lua methods
     *
     * @return array|null
     */
    public function register()
    {
        $lib = [
        'get_ship' => [ $this, 'getShip' ],
        'get_ground_vehicle' => [ $this, 'getGroundVehicle' ],
        'get_manufacturer' => [ $this, 'getManufacturer' ],
        'get_comm_link_metadata' => [ $this, 'getCommLinkMetadata' ],
        ];

        return $this->getEngine()->registerInterface( __DIR__ . '/mw.ext.Apiunto.lua', $lib, [] );
    }

    /**
     * Requests a single ship
     *
     * @return array Shipdata
     */
    public function getShip()
    {
        $params = func_get_args();

        $repository = new ShipRepository(
            static::$client, [
            self::QUERY => $params[ 0 ],
            self::LOCALE => $params[ 1 ],
            ] 
        );

        return [ $repository->getVehicle() ];
    }

    /**
     * Requests a single ground vehicle
     *
     * @return array Groundvehicle data
     */
    public function getGroundVehicle()
    {
        $params = func_get_args();

        $repository = new GroundVehicleRepository(
            static::$client, [
            self::QUERY => $params[ 0 ],
            self::LOCALE => $params[ 1 ],
            ] 
        );

        return [ $repository->getVehicle() ];
    }

    /**
     * Requests a single manufacturer
     *
     * @return array Manufacturer data
     */
    public function getManufacturer()
    {
        $params = func_get_args();

        $repository = new ManufacturerRepository(
            static::$client, [
            self::QUERY => $params[ 0 ],
            self::LOCALE => $params[ 1 ],
            ] 
        );

        return [ $repository->getManufacturer() ];
    }

    /**
     * Requests metadata about a single comm link
     *
     * @return array Comm Link Metadata
     */
    public function getCommLinkMetadata()
    {
        $params = func_get_args();

        $repository = new CommLinkRepository(
            static::$client, [
            self::QUERY => $params[ 0 ]
            ] 
        );

        return [ $repository->getCommLinkMetadata() ];
    }

    /**
     * Loads a config value for a given key from the main config
     * Returns null on if an ConfigException was thrown
     *
     * @param  string $key The config key
     * @return mixed|null
     */
    private function getConfigValue( $key )
    {
        try {
            $value = MediaWikiServices::getInstance()->getMainConfig()->get( $key );
        } catch ( ConfigException $e ) {
            wfLogWarning(
                sprintf(
                    'Could not get config for "$wg%s". %s', $key,
                    $e->getMessage()
                )
            );
            $value = null;
        }

        return $value;
    }
}
