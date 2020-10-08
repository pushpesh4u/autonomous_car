<?php
namespace Autonomous\Controllers;

use Autonomous\Controllers\Constants\Tracking;
use Autonomous\Controllers\Common\RoadInterface;

class UrbanController implements RoadInterface {
	/**
	 * Total distance(max) to be covered
	 */
	private $distanceToCover;

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

		// Garage to starting point for mapping
		$distance_car_can_travel_more_without_refuel = Tracking::MAX_TRAVEL_DISTANCE_AFTER_REFUELING - $this->getGarageDistance();

		$remaining_mapping_distance = $this->distanceToCover + $this->getTrafficDistanceNeeded($this->distanceToCover , Tracking::URBAN_RANGE_TRAFFIC_DEPRECIATION_PERCENT);	

		while($remaining_mapping_distance > 0) {

			$distance_car_can_travel_more_without_refuel = $distance_car_can_travel_more_without_refuel - 0.25 * $distance_car_can_travel_more_without_refuel;


			// CASE 1 :
			if($distance_car_can_travel_more_without_refuel > $remaining_mapping_distance) {

				
				// Travel the remaining disance 
				$distance_car_can_travel_more_without_refuel -= $remaining_mapping_distance;

				// check if we have enuf petrol to reach garage , if not then refuel once again
				if($distance_car_can_travel_more_without_refuel < $distanceOfRoadFromGarage) {
					$numTimesRefueled++;
				}

				break;
			} else if($distance_car_can_travel_more_without_refuel == $remaining_mapping_distance) { // CASE 2


				// We need refuelling to reach to garage
				$numTimesRefueled++;
				
				break;
			} else { // CASE 3 


				/**
				*	Travel distance that we can before refuel Before Refuel
				*/


				$remaining_mapping_distance -= $distance_car_can_travel_more_without_refuel - (Tracking::REFUEL_ROUND_TRIP_DISTANCE / 2);
				// Refuel 
				$numTimesRefueled++;
				$distance_car_can_travel_more_without_refuel = Tracking::MAX_TRAVEL_DISTANCE_AFTER_REFUELING - (Tracking::REFUEL_ROUND_TRIP_DISTANCE / 2);
			}


			// $remaining_mapping_distance -= $remaining_mapping_distance + $this->getTrafficDistanceNeeded($remaining_mapping_distance , Tracking::URBAN_RANGE_TRAFFIC_DEPRECIATION_PERCENT);	

		}

		$distance_travelled_in_refuelling = ($numTimesRefueled * Tracking::REFUEL_ROUND_TRIP_DISTANCE);

		$totalDistanceTraveled = $this->distanceToCover + (2 * $distanceOfRoadFromGarage) +  $distance_travelled_in_refuelling;

		$totalTimeSpent = $this->distanceToCover / $this->getSpeedLimit()  + ((2 * $distanceOfRoadFromGarage) / Tracking::SPEED_LIMIT_KMPH) +  ($distance_travelled_in_refuelling / $this->getSpeedLimit()) + ($numTimesRefueled * Tracking::TIME_TO_REFUEL_HOURS);

		return [ round( $totalTimeSpent, 2 ), $numTimesRefueled, $totalDistanceTraveled ];
	}

	public function getGarageDistance() {
		return Tracking::DISTANCE_GARAGE_TO_URBAN_AREA;
	}

	public function getDistanceBeforeRefuel() {
		return Tracking::MAX_TRAVEL_DISTANCE_AFTER_REFUELING * (1 - Tracking::URBAN_RANGE_TRAFFIC_DEPRECIATION_PERCENT);
	}

	public function getSpeedLimit() {
		return Tracking::SPEED_LIMIT_KMPH * 0.75 ;
	}

	public function getTrafficDistanceNeeded($distance , $precentIncrease) {
		// return $distance * 0.33;
		// return $distance;
		return 0;
	}
}