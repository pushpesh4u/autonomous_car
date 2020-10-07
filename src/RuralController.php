<?php
namespace Autonomous\Controllers;

use Autonomous\Controllers\Constants\Tracking;
use Autonomous\Controllers\Common\RoadInterface;

class RuralController implements RoadInterface {
	/**
	 * Type of road to be covered
	 */
	private $roadType;

	/**
	 * Total distance(max) to be covered
	 */
	private $distanceToCover;

	/**
	 * Car starts with a full tank
	 */
	private $fuelStatus = 1;

	public function __construct() {
		//$this->roadType = $roadType;
		//$this->distanceToCover = $distanceToCover;
	}

	public function getMetrics() : array {
		$result = [ '', '', '' ];

		//
		return $result;
	}

	public function getGarageDistance() {
		return Tracking::DISTANCE_GARAGE_TO_RURAL_AREA;
	}
}