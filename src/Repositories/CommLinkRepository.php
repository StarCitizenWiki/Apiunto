<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 11.10.2018
 * Time: 13:57
 */

namespace MediaWiki\Extension\Apiunto\Repositories;

class CommLinkRepository extends AbstractRepository
{
    const API_ENDPOINT = 'api/comm-links';

    /**
     * @return string
     */
    public function getCommLinkMetadata() {
        try {
            $response = $this->client->get( $this->makeUrl() );
        }
        catch ( ConnectException  $e ) {
            return \GuzzleHttp\json_encode( [
                'error' => 500,
                'message' => 'Could not connect to Api',
            ] );
        }
        catch ( ClientException $e ) {
            return $e->getResponse()->getBody()->getContents();
        }

        return $response->getBody()->getContents();
    }
}
