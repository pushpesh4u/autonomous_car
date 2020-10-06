<?php
require __DIR__ . '/vendor/autoload.php';
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class CliSetup extends CLI
{
    // register options and arguments
    protected function setup(Options $options)
    {
        $options->setHelp('Find the mapping time, times refuelled and distance travelled for an autonomous car for a given set of options.');

        $options->registerOption('version', 'print version', 'v');
		$options->registerOption('road_type', 'Type of road. Can be "rural" or "urban"', 't');
		$options->registerOption('road_length', 'Max. distance to travel (in kms)', 'd');
    }

    // implement your code
    protected function main(Options $options)
    {
        if ($options->getOpt('version')) {
            $this->info('0.0.1');
        } else {
            echo $options->help();
        }
    }
}

$cli = new CliSetup();

$cli->run();