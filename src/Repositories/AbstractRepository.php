<?php declare( strict_types=1 );
/**
 * User: Hannes
 * Date: 18.08.2018
 * Time: 14:24
 */

namespace MediaWiki\Extension\Apiunto\Repositories;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

abstract class AbstractRepository
{
    const API_ENDPOINT = '';
    const LOCALE = 'locale';

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
     * @param Client     $client  Request Client
     * @param null|array $options Request options gets appended to the request url
     */
    public function __construct( Client $client, $options = null )
    {
        $this->client = $client;

        if ( is_array( $options ) ) {
            $this->options = $options;
        }
    }

    /**
     * @param array $options
     */
    public function setOptions( array $options )
    {
        $this->options = $options;
    }

    /**
     * Generates the request url appending options as query parameters
     *
     * @return string
     */
    protected function makeUrl()
    {
        $format = '%s/%s';

        if ( isset( $this->options[ self::LOCALE ] ) && !empty( $this->options[ self::LOCALE ] ) ) {
            $format .= '?locale=%s';
            $url =
            sprintf(
                $format, static::API_ENDPOINT, $this->options[ 'query' ],
                $this->options[ self::LOCALE ]
            );
        } else {
            $url = sprintf( $format, static::API_ENDPOINT, $this->options[ 'query' ] );
        }

        return $url;
    }

    /**
     * @param  GuzzleException|null $exception
     * @return mixed|string
     */
    protected function responseFromException( $exception)
    {
        if ( $exception === null || $exception->getResponse() === null ) {
            return 'Response is empty';
        }

        return $exception->getResponse()->getBody()->getContents();
    }
}
