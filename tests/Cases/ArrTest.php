<?php

namespace Collection\Tests\Cases;

use Collection\Collection;
use Collection\Tools\Arr;
use Exception;
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
class ArrTest extends PHPUnit_Framework_TestCase
{


    public function testArrAdd()
    {
        $array    = [
                     1,
                     2,
                     3,
                    ];
        $result   = Arr::add($array, 3, 4);
        $expected = [
                     1,
                     2,
                     3,
                     4,
                    ];
        $this->assertEquals($expected, $result);

    }//end testArrAdd()


    public function testBuildArrayClousure()
    {
        $array  = [
                   1,
                   2,
                   3,
                  ];
        $result = Arr::build(
            $array,
            function ($key, $item) {
                    $result = [
                               $key,
                               ($item * 2),
                              ];
                    return $result;
            }
        );
        $expected = [
                     2,
                     4,
                     6,
                    ];
        $this->assertEquals($expected, $result);

    }//end testBuildArrayClousure()


    public function testCollapse()
    {
        $array    = [
                     [
                      1,
                      2,
                      3,
                     ],                     [
                                             4,
                                             5,
                                             6,
                                            ],
                    ];
        $result   = Arr::collapse($array);
        $expected = [
                     1,
                     2,
                     3,
                     4,
                     5,
                     6,
                    ];
        $this->assertEquals($expected, $result);

    }//end testCollapse()


    public function testDivideArray()
    {
        $array    = [
                     1,
                     2,
                     3,
                    ];
        $result   = Arr::divide($array);
        $expected = [
                     [
                      0,
                      1,
                      2,
                     ],                     [
                                             1,
                                             2,
                                             3,
                                            ],
                    ];
        $this->assertEquals($expected, $result);

    }//end testDivideArray()


    public function testDotArray()
    {
        $array    = [
                     1,
                     2,
                     3,
                    ];
        $result   = Arr::dot($array, ':');
        $expected = [
                     ':0' => 1,
                     ':1' => 2,
                     ':2' => 3,
                    ];
        $this->assertEquals($expected, $result);

    }//end testDotArray()


    public function testExceptArray()
    {
        $array    = [
                     1,
                     2,
                     3,
                    ];
        $result   = Arr::except($array, 1);
        $expected = [
                     0 => 1,
                     2 => 3,
                    ];
        $this->assertEquals($expected, $result);

    }//end testExceptArray()


    public function testHasArray()
    {
        $array = [
                  1,
                  2,
                  3,
                 ];
        $this->assertTrue(Arr::has($array, 0));
        $this->assertTrue(Arr::has($array, 1));
        $this->assertTrue(Arr::has($array, 2));

        $this->assertFalse(Arr::has($array, 4));
        $this->assertFalse(Arr::has($array, 5));
        $this->assertFalse(Arr::has($array, 6));

    }//end testHasArray()


    public function testIsAssoc()
    {
        $this->assertFalse(Arr::isAssoc([1, 2, 3]));
        $this->assertTrue(Arr::isAssoc(['nome' => 'Thiago']));

    }//end testIsAssoc()


    public function testArrayOnly()
    {
        $array    = [
                     1,
                     2,
                     3,
                    ];
        $result   = Arr::only($array, 2);
        $expected = [2 => 3];
        $this->assertEquals($expected, $result);

    }//end testArrayOnly()


    public function testArraySet()
    {
        $array = [
                  1,
                  2,
                  3,
                 ];
        Arr::set($array, 3, 55);
        $this->assertEquals([1, 2, 3, 55], $array);

    }//end testArraySet()


    public function testArraySetComKeyNull()
    {
        $array = [
                  1,
                  2,
                  3,
                 ];
        Arr::set($array, null, 55);
        $this->assertEquals(55, $array);

    }//end testArraySetComKeyNull()


    public function testArraySetComKeyArray()
    {
        $array = [
                  1,
                  2,
                  3,
                 ];
        Arr::set($array, '1.2', 55);
        $this->assertEquals([0 => 1, 1 => [2 => 55], 2 => 3], $array);

    }//end testArraySetComKeyArray()


    public function testArraySort()
    {
        $array  = [
                   1,
                   2,
                   3,
                  ];
        $result = Arr::sort(
            $array,
            function ($array) {
                    return $array;
            }
        );

        $expected = [
                     1,
                     2,
                     3,
                    ];
        $this->assertEquals($expected, $result);

    }//end testArraySort()


    public function testArrayWhere()
    {
        $array  = [
                   1,
                   2,
                   3,
                  ];
        $result = Arr::where(
            $array,
            function ($key, $value) {
                    unset($key);
                    return $value > 1;
            }
        );
        $this->assertEquals([1 => 2, 2 => 3], $result);

    }//end testArrayWhere()


    public function testArrayCollapseCollection()
    {
        $collection = new Collection([[1, 2, 3], [4, 5]]);
        $result     = Arr::collapse($collection);
        $expected   = [
                       1,
                       2,
                       3,
                       4,
                       5,
                      ];
        $this->assertEquals($expected, $result);

    }//end testArrayCollapseCollection()


    public function testSortRecursive()
    {
        $array    = [
                     1,
                     2,
                     3,
                     [
                      4,
                      5,
                      6,
                     ],
                    ];
        $result   = Arr::sortRecursive($array);
        $expected = [
                     1,
                     2,
                     3,
                     [
                      4,
                      5,
                      6,
                     ],
                    ];
        $this->assertEquals($expected, $result);

    }//end testSortRecursive()


    public function testMacroable()
    {
        Arr::macro(
            'filterCollection',
            function ($array) {
                $collection = new Collection($array);
                $result     = $collection->filter(
                    function ($value) {
                        return $value > 2;
                    }
                );
                return $result->toArray();
            }
        );
        $this->assertTrue(Arr::hasMacro('filterCollection'));

        $staticResult   = Arr::filterCollection([1, 2, 3]);
        $staticExpected = [2 => 3];
        $this->assertEquals($staticExpected, $staticResult);

        $objectResult   = (new Arr())->filterCollection([1, 2, 3]);
        $objectExpected = [2 => 3];
        $this->assertEquals($objectExpected, $objectResult);

    }//end testMacroable()


    public function testMacroableNoClosure()
    {
        Arr::macro('collection', array((new Collection()), 'make'));
        $this->assertTrue(Arr::hasMacro('filterCollection'));
        $result   = Arr::collection([1, 2, 3]);
        $expected = [
                     1,
                     2,
                     3,
                    ];
        $this->assertEquals($expected, $result->toArray());

    }//end testMacroableNoClosure()


    public function testMacroableMethodNaoRegistrado()
    {
        try {
            Arr::naoExiste();
        } catch (Exception $exc) {
            $this->assertEquals('Method naoExiste does not exist.', $exc->getMessage());
            return;
        }

        $this->fail('Uma exception não foi lançada');

    }//end testMacroableMethodNaoRegistrado()


    public function testMacroableNoClosureObjectContext()
    {
        Arr::macro('collection', array((new Collection()), 'make'));
        $this->assertTrue(Arr::hasMacro('filterCollection'));
        $arr      = new Arr();
        $result   = $arr->collection([1, 2, 3]);
        $expected = [
                     1,
                     2,
                     3,
                    ];
        $this->assertEquals($expected, $result->toArray());

    }//end testMacroableNoClosureObjectContext()


    public function testMacroableMethodNaoRegistradoObjectContext()
    {
        try {
            $arr = new Arr();
            $arr->methodNaoExiste();
        } catch (Exception $exc) {
            $this->assertEquals('Method methodNaoExiste does not exist.', $exc->getMessage());
            return;
        }

        $this->fail('Uma exception não foi lançada');

    }//end testMacroableMethodNaoRegistradoObjectContext()


    public function testCollapseObjectTypeCollection()
    {
        $collection1 = new Collection([1, 2, 3]);
        $collection2 = new Collection([4, 5, 6]);
        $result      = Arr::collapse([$collection1, $collection2]);
        $expected    = [
                        1,
                        2,
                        3,
                        4,
                        5,
                        6,
                       ];
        $this->assertEquals($expected, $result);

    }//end testCollapseObjectTypeCollection()


    public function testDotComValoresArray()
    {
        $array    = [
                     [
                      1,
                      2,
                     ],                     [
                                             3,
                                             4,
                                            ],
                    ];
        $result   = Arr::dot($array, '-');
        $expected = [
                     '-0.0' => 1,
                     '-0.1' => 2,
                     '-1.0' => 3,
                     '-1.1' => 4,
                    ];
        $this->assertEquals($expected, $result);

    }//end testDotComValoresArray()


    public function testForgetForceCoverage()
    {
        $array    = [
                     '1.2' => 'Thiago',
                     '1.3' => 'Diego',
                     '1.4' => ['1' => 'Equipe'],
                    ];
        $keys     = [
                     '1.2',
                     '1.3',
                     '1.4',
                    ];
        $result   = Arr::forget($array, $keys);
        $expected = null;
        $this->assertEquals($expected, $result);

    }//end testForgetForceCoverage()


    public function testGetForceCoverage()
    {
        $array    = [
                     1,
                     2,
                     3,
                    ];
        $result   = Arr::get($array, null);
        $expected = [
                     1,
                     2,
                     3,
                    ];
        $this->assertEquals($expected, $result);

    }//end testGetForceCoverage()
    public function testFetchForceCoverage()
    {
        $result = Arr::fetch(['0'],'0.teste');
        $this->assertEmpty($result);
    }
    public function testCoverageTotalGet() {
    	$result = Arr::get([1], '0.1');
    	$this->assertNull($result);
    	
    }
    public function testCoverageTotalHas() {
    	$result = Arr::has([1], '0.0');
    	$this->assertNull($result);
    	 
    }

}//end class
