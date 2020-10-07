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
		$result = [ '', '', '' ];

		// TODO
		return $result;
	}

	public function getGarageDistance() {
		return Tracking::DISTANCE_GARAGE_TO_URBAN_AREA;
	}

	public function getDistanceBeforeRefuel() {
		return $this->distanceToCover * (1 - Tracking::RURAL_RANGE_TRAFFIC_DEPRECIATION_PERCENT);
	}

	public function getSpeedLimit() {
		return Tracking::SPEED_LIMIT_KMPH;
	}
}