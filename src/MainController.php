<?php
namespace Autonomous\Controllers;

use Autonomous\Controllers\Constants\Tracking;

class MainController {
	/**
	 * Type of road to be covered
	 */
	private $roadType;

	public function __construct( string $roadType, int $distanceToCover ) {
		$this->roadType = $roadType;
		$this->distanceToCover = $distanceToCover;
	}

	public function getMetrics() : array {
		$result = [ '', '', '' ];

		switch( $this->roadType ) {
			case Tracking::URBAN_ROAD:
				$obj = new UrbanController($this->distanceToCover);
				break;
			case Tracking::RURAL_ROAD:
				$obj = new RuralController($this->distanceToCover);
				break;
		}

		$result = $obj->getMetrics();
		return $result;
	}
}