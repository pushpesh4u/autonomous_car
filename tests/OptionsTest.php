<?php
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

use Autonomous\Controllers\Constants\Tracking;

class OptionsTestChild extends Options
{

    public $args;
}

class OptionsTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider optionDataProvider
     *
     * @param string $option
     * @param string $value
     * @param string $argument
     */
    function test_optionvariants(
        $option,
        $value,
        $argument
    ) {
        $options = new OptionsTestChild();
        $options->registerOption('exclude', 'exclude files', 'x', 'file');

        $options->args = array($option, $value, $argument);
        $options->parseOptions();

        $this->assertEquals($value, $options->getOpt('exclude'));
        $this->assertEquals([$argument], $options->args);
        $this->assertFalse($options->getOpt('nothing'));
    }

    /**
     * @return array
     */
    public function optionDataProvider() {
        return array(
            array('-x', 'foo', 'bang'),
            array('--exclude', 'foo', 'bang'),
            array('-x', 'foo-bar', 'bang'),
            array('--exclude', 'foo-bar', 'bang'),
            array('-x', 'foo', 'bang--bang'),
            array('--exclude', 'foo', 'bang--bang'),
        );
    }

	function test_roadType() {
		$options = new OptionsTestChild();
		$options->registerOption('road_type', 'Type of road. Can be "rural" or "urban"', '', 'rural|urban');

		$options->args = array('--road_type=urban', 'urban');
        $options->parseOptions();

		$validRoadTypes = [ Tracking::URBAN_ROAD, Tracking::RURAL_ROAD ];

		$this->assertEquals('urban', $options->getOpt('road_type'));
        $this->assertEquals(array('urban'), $options->args);
        $this->assertContains('urban', $options->getOpt('road_type'));
	}

	function test_roadLength() {
		$options = new OptionsTestChild();
		$options->registerOption('road_length', 'Max. distance to travel (in kms)', '', 'distance');

		$options->args = array('--road_length=900', '900');
        $options->parseOptions();

		$this->assertEquals('900', $options->getOpt('road_length'));
        $this->assertEquals(array('900'), $options->args);
        $this->assertFalse($options->getOpt('nothing'));
	}

    function test_simplelong2()
    {
        $options = new OptionsTestChild();
        $options->registerOption('exclude', 'exclude files', 'x', 'file');

        $options->args = array('--exclude=foo', 'bang');
        $options->parseOptions();

        $this->assertEquals('foo', $options->getOpt('exclude'));
        $this->assertEquals(array('bang'), $options->args);
        $this->assertFalse($options->getOpt('nothing'));
    }

    function test_complex()
    {
        $options = new OptionsTestChild();

        $options->registerOption('plugins', 'run on plugins only', 'p');
        $options->registerCommand('status', 'display status info');
        $options->registerOption('long', 'display long lines', 'l', false, 'status');

        $options->args = array('-p', 'status', '--long', 'foo');
        $options->parseOptions();

        $this->assertEquals('status', $options->getCmd());
        $this->assertTrue($options->getOpt('plugins'));
        $this->assertTrue($options->getOpt('long'));
        $this->assertEquals(array('foo'), $options->args);
    }
}