<?php
require __DIR__ . '/vendor/autoload.php';
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

use Autonomous\Controllers\MainController;
use Autonomous\Controllers\Constants\Tracking;

class CliSetup extends CLI
{
    // override the default log level
    protected $logdefault = 'info';

    // register options and arguments
    protected function setup(Options $options)
    {
        $options->setHelp('Find the mapping time, times refuelled and distance travelled for an autonomous car for a given set of options.');

        $options->registerOption('version', 'print version', 'v');
		$options->registerOption('road_type', 'Type of road. Can be "rural" or "urban"', '', 'rural|urban');
		$options->registerOption('road_length', 'Max. distance to travel (in kms)', '', 'distance');
    }

    // implement your code
    protected function main(Options $options)
    {
		$this->colors->enable();

		$road_type = trim( $options->getOpt('road_type') ) ?? '';
		$distance = ( int ) trim( $options->getOpt('road_length') ) ?? '';
		
		if( !in_array( $road_type, [ Tracking::URBAN_ROAD, Tracking::RURAL_ROAD ] ) ) {
			$road_type = false;
		}
		
		if( !is_int( $distance ) || ( $distance < 0 ) ) {
			$distance = false;
		}

        if ($options->getOpt('version')) {
            $this->info('0.0.1');
        } else if( !$distance || !$road_type ) {
			$this->log('error', "Please specify proper arguments.\n\n\n");
			echo $options->help();
        } else {
			$cal = new MainController( $road_type, $distance );
			list( $timeSpent, $timeRefuelled, $distanceTravelled ) = $cal->getMetrics();

			$this->success( 'Total time spent on the mapping task : ' . $timeSpent . ' hours.' );
			$this->success( 'Number of times refueled : ' . $timeRefuelled );
			$this->success( 'Total distance travelled : ' . $distanceTravelled );
		}

		$this->colors->disable();
    }
}

$cli = new CliSetup();

$cli->run();