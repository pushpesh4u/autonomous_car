<?php
namespace Autonomous\Controllers;

use Autonomous\Controllers\Constants\Tracking;

class MainController {
	/**
	 * Type of road to be covered
	 */
	private $roadType;

	/**
	 * Total distance(max) to be covered
	 */
	private $distanceToCover;

	/**
	 * Distance of garage
	 */
	private $garageDistance;

	/**
	 * Car starts with a full tank
	 */
	private $fuelStatus = 1;

	public function __construct( string $roadType, int $distanceToCover ) {
		$this->roadType = $roadType;
		$this->distanceToCover = $distanceToCover;

		$this->garageDistance = $this->getGarageDistance();
	}

	public function getMetrics() : array {
		$result = [ '', '', '' ];

		switch( $this->roadType ) {
			case Tracking::URBAN_ROAD:
				$obj = new UrbanController();
				break;
			case Tracking::RURAL_ROAD:
				$obj = new RuralController();
				break;
		}

		$result = $obj->getMetrics();
		return $result;
	}

	private function getGarageDistance() : int {
		switch( $this->roadType ) {
			case Tracking::URBAN_ROAD:
				return Tracking::DISTANCE_GARAGE_TO_URBAN_AREA;
				break;
			case Tracking::RURAL_ROAD:
				return Tracking::DISTANCE_GARAGE_TO_RURAL_AREA;
				break;
		}
	}
}