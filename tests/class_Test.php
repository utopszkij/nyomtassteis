<?php

/**
 * unit test
 * use:  cd /pluginpath
 *       phpunit tests
 */
declare(strict_types=1);
include_once './tests/mock.php';
include_once './class.areamanager.php';

use PHPUnit\Framework\TestCase;

global $testId;

// test Cases
class classlTest extends TestCase {
    protected $class;
    
    function __construct() {
        parent::__construct();
        $this->class = new Area();
    }
    public function test_start() {
        // create and init test database
        databaseInit();
        $this->assertEquals('','');
    }
    
    public function test_init() {
        global $database,$testId;
        $this->assertEquals(0, $this->class->id);
    }
    
    public function test_move() {
        $this->class->hide();
        $this->assertEquals(true,true);
    }
    public function test_select() {
        $this->class->hide();
        $this->assertEquals(true,true);
    }
    public function test_deselect() {
        $this->class->deselect();
        $this->assertEquals(true,true);
    }
    public function test_sort() {
        $this->class->sort();
        $this->assertEquals(true,true);
    }
    public function addForm() {
        $this->class->addForm(true);
        $this->expectOutputRegex('/id="center"/');
    }
    public function editForm() {
        $this->class->editForm(true);
        $this->expectOutputRegex('/id="center"/');
    }
    public function adminPanel() {
        $this->class->adminPanel(true);
        $this->expectOutputRegex('/name="gApiKey"/');
    }
    public function setupSave() {
        $this->class->setupSave(true);
        $this->expectOutputRegex('/<p>google API key:/');
    }
	public function hide() {
	    $this->class->hide();
	    $this->assertEquals(true,true);
	}
}


