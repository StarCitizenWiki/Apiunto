<?php declare( strict_types=1 );

namespace MediaWiki\Extension\Apiunto\Repositories;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

/**
 * Star Citizen Manufacturer data
 */
class ManufacturerRepository extends AbstractRepository
{
    const API_ENDPOINT = 'api/manufacturers';

    /**
     * Star Citizen Manufacturer
     * https://docs.star-citizen.wiki/star_citizen_api.html#einzelner-hersteller
     *
     * @return string JSON data
     */
    public function getManufacturer()
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
