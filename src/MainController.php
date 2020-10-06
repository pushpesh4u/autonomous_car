<?php
namespace Autonomous\Controllers;

use Autonomous\Controllers\Constants\Tracking;

class MainController {
	private $roadType;
	private $distance;

	public function __construct( string $roadType, int $distance ) {
		$this->roadType = $roadType;
		$this->distance = $distance;
	}

	public function getMetrics() : array {
		// TODO
		return ['','',''];
	}

	private function getGarageDistance() {
		switch( $this->roadType ) {
			case Tracking::URBAN_ROAD:
				break;
			case Tracking::RURAL_ROAD:
				break;
		}
	}
}