<?php declare( strict_types=1 );

namespace MediaWiki\Extension\Apiunto\Repositories\Vehicle;

/**
 * Ships
 * https://docs.star-citizen.wiki/star_citizen_api.html#raumschiffe
 */
class ShipRepository extends AbstractVehicleRepository
{
    const API_ENDPOINT = 'api/ships';
}
