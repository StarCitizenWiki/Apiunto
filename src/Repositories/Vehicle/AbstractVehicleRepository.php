<?php declare( strict_types=1 );

namespace MediaWiki\Extension\Apiunto\Repositories\Vehicle;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use MediaWiki\Extension\Apiunto\Repositories\AbstractRepository;

/**
 * Base vehicle class
 */
abstract class AbstractVehicleRepository extends AbstractRepository
{
    /**
     * Ship / Ground Vehicles
     *
     * @see ShipRepository
     * @see GroundVehicleRepository
     *
     * @return string JSON data
     */
    public function getVehicle()
    {
        try {
            $response = $this->client->get( $this->makeUrl() );
        } catch ( ConnectException $e ) {
            return \GuzzleHttp\json_encode(
                [
                'error' => 500,
                'message' => 'Could not connect to Api',
                 ] 
            );
        } catch ( ClientException $e ) {
            return $this->responseFromException( $e );
        }

        return $response->getBody()->getContents();
    }
}
