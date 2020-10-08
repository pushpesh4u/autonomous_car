<?php
namespace Autonomous\Controllers;

use Autonomous\Controllers\Constants\Tracking;
use Autonomous\Controllers\Common\RoadInterface;
use Autonomous\Controllers\Common\Helper;

class RuralController implements RoadInterface {
	/**
	 * Total distance(max) to be covered
	 */
	private $distanceToCover;
	private $helper;

	public function __construct(int $distanceToCover ) {
		$this->distanceToCover = $distanceToCover;
		$this->helper = new Helper;
	}
	
	/*
	*@input:void
	*@output:array
	*@author:Ravi Ranjan
	*/
	public function getMetrics() : array {


		/**
		 * Total refuelds needed
		 */
		$distanceOfRoadFromGarage = $this->getGarageDistance();
		$distance_car_can_travel_more_without_refuel = Tracking::MAX_TRAVEL_DISTANCE_AFTER_REFUELING - $this->getGarageDistance();
		$remaining_mapping_distance = $this->distanceToCover;
		$traffic_constant = Tracking::RURAL_RANGE_TRAFFIC_DEPRECIATION_PERCENT;
		$numTimesRefueled = $this->helper->getNumberOfRefuelsNeeded($distanceOfRoadFromGarage , $distance_car_can_travel_more_without_refuel , $remaining_mapping_distance , $traffic_constant);
		
		/**
		 * Total distance travelled
		 */
		$distance_travelled_in_refuelling = ($numTimesRefueled * Tracking::REFUEL_ROUND_TRIP_DISTANCE);
		$totalDistanceTraveled = $this->distanceToCover + (2 * $distanceOfRoadFromGarage) +  $distance_travelled_in_refuelling;

		/**
		 * Total time spent
		 */
		$totalTimeSpent = $this->distanceToCover / $this->getSpeedLimit()  + ((2 * $distanceOfRoadFromGarage) / Tracking::SPEED_LIMIT_KMPH) +  ($distance_travelled_in_refuelling / $this->getSpeedLimit()) + ($numTimesRefueled * Tracking::TIME_TO_REFUEL_HOURS);

		return [ round( $totalTimeSpent, 2 ), $numTimesRefueled, $totalDistanceTraveled ];
	}

	public function getGarageDistance() {
		return Tracking::DISTANCE_GARAGE_TO_RURAL_AREA;
	}

	public function getSpeedLimit() {
		return Tracking::SPEED_LIMIT_KMPH * Tracking::SPEED_LIMIT_RURAL_RELAXATION_PERCENT;
	}

}