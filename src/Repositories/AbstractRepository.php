<?php

declare( strict_types=1 );

namespace MediaWiki\Extension\Apiunto\Repositories;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use MediaWiki\Extension\Apiunto\Scribunto_ApiuntoLuaLibrary;

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
	 * Perform the request
	 *
	 * @return string
	 */
	protected function request(): string {
		try {
			$url =
				sprintf( '%s/%s', static::API_ENDPOINT,
					$this->options[Scribunto_ApiuntoLuaLibrary::IDENTIFIER] );

			$response =
				$this->client->get( $url,
					['query' => $this->options[Scribunto_ApiuntoLuaLibrary::QUERY_PARAMS]] );
		} catch ( ConnectException $e ) {
			return \GuzzleHttp\json_encode( [
				'error' => 500,
				'message' => 'Could not connect to Api',
			] );
		} catch ( ClientException $e ) {
			return $this->responseFromException( $e );
		}

		return $response->getBody()->getContents();
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
}
