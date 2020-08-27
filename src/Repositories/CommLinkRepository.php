<?php declare(strict_types = 1);

namespace MediaWiki\Extension\Apiunto\Repositories;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

/**
 * Star Citizen Comm-Link metadata
 */
class CommLinkRepository extends AbstractRepository
{
    const API_ENDPOINT = 'api/comm-links';

    /**
     * Comm-Link Metadata
     * https://docs.star-citizen.wiki/star_citizen_api.html#einzelner-comm-link
     *
     * @return string
     */
    public function getCommLinkMetadata()
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
