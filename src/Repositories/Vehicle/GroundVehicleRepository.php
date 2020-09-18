<?php declare( strict_types=1 );

namespace MediaWiki\Extension\Apiunto\Repositories\Vehicle;

/**
 * Ground Vehicles
 * https://docs.star-citizen.wiki/star_citizen_api.html#bodenfahrzeuge
 */
class GroundVehicleRepository extends AbstractVehicleRepository
{
    public const API_ENDPOINT = 'api/vehicles';
}
