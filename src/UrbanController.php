<?php
namespace Autonomous\Controllers;

use Autonomous\Controllers\Constants\Tracking;
use Autonomous\Controllers\Common\RoadInterface;

class UrbanController implements RoadInterface {
	/**
	 * Total distance(max) to be covered
	 */
	private $distanceToCover;

	/**
	 * Car starts with a full tank
	 */
	private $fuelStatus = 1;

	public function __construct( int $distanceToCover ) {
		$this->distanceToCover = $distanceToCover;
	}

	public function getMetrics() : array {
		$totalTimeSpent = 0;
		$numTimesRefueled = 0;
		$totalDistanceTraveled = 0;

		/**
		 * Car starts from a garage.
		 * At this point, assume the fuel tank is full
		 * Car should return to the garage for the task to complete
		 * or the distance specified should be traveled.
		 */
		
		// discount the distance of the road from the garage. Going from the garage to the road and coming back
		$distanceOfRoadFromGarage = $this->getGarageDistance();

		$totalDistanceToTravel = $this->distanceToCover - 2 * $distanceOfRoadFromGarage;

		// this is already traveled before starting the mapping
		$totalDistanceTraveled += $distanceOfRoadFromGarage;

		for( $i = 1; ( $i < $totalDistanceToTravel ) && ( $totalDistanceToTravel > 0 ); $i--, $totalDistanceTraveled++, $totalDistanceToTravel-- ) {
			if( $totalDistanceTraveled == ( 1 + $numTimesRefueled ) * $this->getDistanceBeforeRefuel() ) {
				$numTimesRefueled++;
				$totalDistanceTraveled += 2 * Tracking::REFUEL_ROUND_TRIP_DISTANCE;
				$totalDistanceToTravel -= 2 * Tracking::REFUEL_ROUND_TRIP_DISTANCE;
				$totalTimeSpent += Tracking::TIME_TO_REFUEL_MINS;
			}

			$totalTimeSpent += round( Tracking::MINUTES_PER_HOUR / $this->getSpeedLimit(), 2 );
			if( $totalDistanceTraveled == $this->getSpeedLimit() ) {
				// $totalTimeSpent += Tracking::MINUTES_PER_HOUR;
			}
		}

		// this is traveled after the mapping while going back to the garage
		$totalDistanceTraveled += $distanceOfRoadFromGarage;

		return [ round( $totalTimeSpent, 2 ), $numTimesRefueled, $totalDistanceTraveled ];
	}

	public function getGarageDistance() {
		return Tracking::DISTANCE_GARAGE_TO_URBAN_AREA;
	}

	public function getDistanceBeforeRefuel() {
		return Tracking::MAX_TRAVEL_DISTANCE_AFTER_REFUELING * (1 - Tracking::URBAN_RANGE_TRAFFIC_DEPRECIATION_PERCENT);
	}

	public function getSpeedLimit() {
		return Tracking::SPEED_LIMIT_KMPH;
	}
}