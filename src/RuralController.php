<?php
namespace Autonomous\Controllers;

use Autonomous\Controllers\Constants\Tracking;
use Autonomous\Controllers\Common\RoadInterface;

class RuralController implements RoadInterface {
	/**
	 * Total distance(max) to be covered
	 */
	private $distanceToCover;

	public function __construct(int $distanceToCover ) {
		$this->distanceToCover = $distanceToCover;
	}

	public function getMetrics() : array {
		$totalTimeSpent = 0;
		$numTimesRefueled = 0;
		$totalDistanceTraveled = 0;

		$route = "rural";
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

		// if($route == "rural") {
			// $remaining_mapping_distance = $this->distanceToCover + $this->getTrafficDistanceNeeded($this->distanceToCover , Tracking::RURAL_RANGE_TRAFFIC_DEPRECIATION_PERCENT);	
			$remaining_mapping_distance = $this->distanceToCover;
		// } else {
		// 	$remaining_mapping_distance = $this->distanceToCover + $this->getTrafficDistanceNeeded($this->distanceToCover , Tracking::URBAN_RANGE_TRAFFIC_DEPRECIATION_PERCENT);	
		// }
		

		while($remaining_mapping_distance > 0) {

			echo "$remaining_mapping_distance=" . $remaining_mapping_distance . PHP_EOL;
			echo "$distance_car_can_travel_more_without_refuel=" . $distance_car_can_travel_more_without_refuel . PHP_EOL;

			// CASE 1 :
			if($distance_car_can_travel_more_without_refuel > $remaining_mapping_distance) {

				echo "case1" . PHP_EOL;
				
				// Travel the remaining disance 
				$distance_car_can_travel_more_without_refuel -= $remaining_mapping_distance;

				// check if we have enuf petrol to reach garage , if not then refuel once again
				if($distance_car_can_travel_more_without_refuel < $distanceOfRoadFromGarage) {
					$numTimesRefueled++;
					echo "refuelling..." . PHP_EOL;
				}

				break;
			} else if($distance_car_can_travel_more_without_refuel == $remaining_mapping_distance) { // CASE 2

				echo "case2" . PHP_EOL;

				// We need refuelling to reach to garage
				$numTimesRefueled++;
				echo "refuelling..." . PHP_EOL;
				
				break;
			} else { // CASE 3 

				echo "case3" . PHP_EOL;
				
				

				/**
				*	Travel distance that we can before refuel Before Refuel
				*/
				$remaining_mapping_distance -= $distance_car_can_travel_more_without_refuel - (Tracking::REFUEL_ROUND_TRIP_DISTANCE / 2);
				echo "refuelling..." . PHP_EOL;
				// Refuel 
				$numTimesRefueled++;
				$distance_car_can_travel_more_without_refuel = Tracking::MAX_TRAVEL_DISTANCE_AFTER_REFUELING - (Tracking::REFUEL_ROUND_TRIP_DISTANCE / 2);
			}

			// if($route == "rural") {
			// 	$remaining_mapping_distance -= $remaining_mapping_distance + $this->getTrafficDistanceNeeded($remaining_mapping_distance , Tracking::RURAL_RANGE_TRAFFIC_DEPRECIATION_PERCENT);	
			// } else {
			// 	$remaining_mapping_distance -= $remaining_mapping_distance + $this->getTrafficDistanceNeeded($remaining_mapping_distance , Tracking::URBAN_RANGE_TRAFFIC_DEPRECIATION_PERCENT);	
			// }
		}

		$distance_travelled_in_refuelling = ($numTimesRefueled * Tracking::REFUEL_ROUND_TRIP_DISTANCE);

		$totalDistanceTraveled = $this->distanceToCover + (2 * $distanceOfRoadFromGarage) +  $distance_travelled_in_refuelling;
		$totalTimeSpent = $this->distanceToCover / $this->getSpeedLimit()  + ((2 * $distanceOfRoadFromGarage) / Tracking::SPEED_LIMIT_KMPH) +  ($distance_travelled_in_refuelling / $this->getSpeedLimit());


		return [ round( $totalTimeSpent, 2 ), $numTimesRefueled, $totalDistanceTraveled ];
	}

	public function getGarageDistance() {
		return Tracking::DISTANCE_GARAGE_TO_RURAL_AREA;
	}

	public function getDistanceBeforeRefuel() {
		return Tracking::MAX_TRAVEL_DISTANCE_AFTER_REFUELING * (1 - Tracking::RURAL_RANGE_TRAFFIC_DEPRECIATION_PERCENT);
	}

	public function getSpeedLimit() {
		return Tracking::SPEED_LIMIT_KMPH * ( 1 + Tracking::SPEED_LIMIT_RURAL_RELAXATION_PERCENT );
	}

	public function getTrafficDistanceNeeded($distance , $precentIncrease) {
		return $distance + $distance * $precentIncrease;
	}


}