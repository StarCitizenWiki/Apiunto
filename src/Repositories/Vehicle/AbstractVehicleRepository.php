<?php declare( strict_types=1 );

namespace MediaWiki\Extension\Apiunto\Repositories\Vehicle;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use MediaWiki\Extension\Apiunto\Repositories\AbstractRepository;

/**
 * User: Hannes
 * Date: 18.08.2018
 * Time: 14:42
 */
class AbstractVehicleRepository extends AbstractRepository {
	/**
	 * @return string
	 */
	public function getVehicle() {
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
