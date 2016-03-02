<?php

namespace Collection\Test\Cases;

use Collection\Collection;
use Collection\Tools\Helpers;
use PHPUnit_Framework_TestCase;

/*
    * To change this license header, choose License Headers in Project Properties.
    * To change this template file, choose Tools | Templates
    * and open the template in the editor.
 */

/**
 * Description of HelpersTest
 *
 * @author thiagoguimaraes
 */
class HelpersTest extends PHPUnit_Framework_TestCase
{


    public function testDataGetReturnDefault()
    {
        $target   = [
                     1,
                     2,
                     3,
                    ];
        $key      = [
                     4,
                     5,
                     6,
                    ];
        $default  = null;
        $result   = Helpers::dataGet($target, $key, $default);
        $expected = $default;
        $this->assertEquals($expected, $result);

    }//end testDataGetReturnDefault()


    public function testDataGetTargetCollection()
    {
        $target   = new Collection([1, 2, 3]);
        $key      = [
                     4,
                     5,
                     6,
                    ];
        $default  = null;
        $result   = Helpers::dataGet($target, $key, $default);
        $expected = $default;
        $this->assertEquals($expected, $result);

    }//end testDataGetTargetCollection()


    public function testDataGetTargetCollectionReturnFirtsElementeKey()
    {
        $target   = new Collection([45 => 45]);
        $key      = [0 => 45];
        $default  = null;
        $result   = Helpers::dataGet($target, $key, $default);
        $expected = 45;
        $this->assertEquals($expected, $result);

    }//end testDataGetTargetCollectionReturnFirtsElementeKey()


    public function testDataGetTargetObject()
    {
        $target   = new \stdClass();
        $key      = [
                     4,
                     5,
                     6,
                    ];
        $default  = null;
        $result   = Helpers::dataGet($target, $key, $default);
        $expected = $default;
        $this->assertEquals($expected, $result);

    }//end testDataGetTargetObject()


    public function testDataGetTargetObjectTryCoverage()
    {
        $target       = new \stdClass();
        $target->nome = 'Thiago';
        $key          = ['nome'];
        $result       = Helpers::dataGet($target, $key, null);
        $expected     = 'Thiago';
        $this->assertEquals($expected, $result);

    }//end testDataGetTargetObjectTryCoverage()


}//end class
