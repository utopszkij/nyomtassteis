<?php

/**
 * unit test
 * use:  cd /pluginpath
 *       phpunit tests
 */
declare(strict_types=1);
include_once './tests/mock.php';
include_once './model.php';

use PHPUnit\Framework\TestCase;

global $testId;

// test Cases
class modelTest extends TestCase {
    protected $model;
    
    function __construct() {
        parent::__construct();
        $this->model = new AreaModel();
    }
    public function test_start() {
        // create and init test database
        databaseInit();
        $this->assertEquals('','');
    }
    
    public function test_insert() {
        global $testId;
        $this->model->id = 0;
        $this->model->name = 'test1';
        $this->model->description = '123';
        $this->model->parent = 0;
        $this->model->type = '';
        $this->model->center = '';
        $this->model->population = 0;
        $this->model->place = 0.0;
        $this->model->poligon = '[]';
        $this->model->enableStart = '2020.01.01';
        $this->model->enableEnd = '2020.12.31';
        $res = $this->model->insert();
        $this->assertEquals(true,$res);
        $testId = $this->model->id;
    }
    
    public function test_read_ok() {
        global $testId;
        $res = $this->model->read($testId);
        $this->assertEquals(true,$res);
        $this->assertEquals('test1',$this->model->name);
        $this->assertEquals('2020.01.01',$this->model->enableStart);
    }
    
    public function test_read_notfound() {
        $res = $this->model->read(128);
        $this->assertEquals(false,$res);
    }
    public function test_modify_ok() {
        global $database, $testId;
        $res = $this->model->read($testId);
        if ($res) {
            $this->model->center='center2';
            $this->model->modify(true);
        }
        $this->assertEquals(true,$res);
        $res = $this->model->read($testId);
        $this->assertEquals(true,$res);
        $this->assertEquals('center2',$this->model->center);
        $this->assertEquals('2020.01.01',$this->model->enableStart);
    }
    public function test_modify_notfound() {
        $res = $this->model->read(129);
        if ($res) {
            $this->model->center='center2';
            $this->model->modify(true);
        }
        $this->assertEquals(false,$res);
    }
    public function test_remove_notfound() {
        global $testId;
        $res = $this->model->read(345);
        if ($res) {
            $this->model->remove(true);
        }
        $this->assertEquals(false,$res);
        // régi megmaradt?
        $res = $this->model->read($testId);
        $this->assertEquals(true,$res);
        $this->assertEquals('test1',$this->model->name);
        $this->assertEquals('2020.01.01',$this->model->enableStart);
    }
    public function test_remove_ok() {
        global $testId, $database;
        $res = $this->model->read($testId);
        if ($res) {
            $this->model->remove(false);
        }
        $this->assertEquals(true,$res);
        // régi megmaradt?
        $res = $this->model->read($testId);
        $this->assertEquals(false,$res);
    }
}

