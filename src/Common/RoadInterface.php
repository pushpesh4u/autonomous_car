<?php
namespace Autonomous\Controllers\Common;

Interface RoadInterface {
	public function getGarageDistance();
	public function getDistanceBeforeRefuel();
	public function getSpeedLimit();
}

?>