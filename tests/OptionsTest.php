<?php
namespace Autonomous\Controllers\Tests;

require __DIR__ . '/../vendor/autoload.php';

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

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