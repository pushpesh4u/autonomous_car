<?php
namespace Autonomous\Controllers\Common;

use Autonomous\Controllers\Constants\Tracking;

class Helper {

	public function getNumberOfRefuelsNeeded($distanceOfRoadFromGarage , $distance_car_can_travel_more_without_refuel , $remaining_mapping_distance , $traffic_constant) : int {
		$numTimesRefueled = 0;

		while($remaining_mapping_distance > 0) {

			$distance_car_can_travel_more_without_refuel = $distance_car_can_travel_more_without_refuel - $traffic_constant * $distance_car_can_travel_more_without_refuel;

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
		}
		return $numTimesRefueled;
	}

}