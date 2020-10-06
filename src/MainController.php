<?php
namespace Autonomous\Controllers;

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
}