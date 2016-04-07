<?php

namespace Collection\Test\Cases;

use Collection\Collection;
use Collection\Test\Fixture\Person;
use Exception;
use PHPUnit_Framework_TestCase;

/**
 * Description of CollectionTest.
 *
 * @author thiagoguimaraes
 */
class CollectionTest extends PHPUnit_Framework_TestCase
{
    public function testMethodObjectItens()
    {
        $person1 = new Person();
        $person2 = new Person();

        $person1->setName('Thiago')->setAge(16)->setDoc(12325440788);
        $person2->setName('Diego')->setAge(30)->setDoc(7894566123);

        $collection = new Collection([$person1, $person2]);
        $filter = $collection->filter(
            function (Person $item) {
                return $item->getAge() < 18;
            }
        );
        $result = $filter->all();
        $this->assertEquals(1, count($result));
        $this->assertEquals('Thiago', $result[0]->getName());
    }

    public function testMethodAll()
    {
        $all = [
            1,
            2,
            3,
        ];
        $collection = new Collection($all);
        $this->assertEquals($all, $collection->all());
    }

    public function testConcatObject()
    {
        $all = [
            1,
            2,
            3,
        ];
        $collection = new Collection($all);
        $result = $collection .= 'Thiago';
        $expected = '[1,2,3]Thiago';

        $this->assertEquals($expected, $result);
    }

    public function testMethodArrayAcess()
    {
        $all = [
            1,
            2,
            3,
        ];
        $collection = new Collection($all);
        $collection[3] = 4;
        $result = [];
        $expected = [
            1,
            2,
            3,
            4,
        ];
        foreach ($collection as $key => $value) {
            $result[$key] = $value;
        }

        $this->assertEquals($expected, $result);
    }

    public function testMethodChunk()
    {
        $all = [
            1,
            2,
            3,
            4,
            5,
            6,
            7,
        ];
        $collection = new Collection($all);
        $chunks = $collection->chunk(4);
        $this->assertEquals([[1, 2, 3, 4], [5, 6, 7]], $chunks->toArray());
    }

    public function testMethodCollapse()
    {
        $all = [
            [
                1,
                2,
                3,
            ], [
                4,
                5,
                6,
            ], [
                7,
                8,
                9,
            ],
        ];
        $collection = new Collection($all);
        $collapse = $collection->collapse();
        $this->assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9], $collapse->all());
    }

    public function testMethodContains()
    {
        $collection = new Collection(['name' => 'Desk', 'price' => 100]);

        $this->assertTrue($collection->contains('Desk'));

        $this->assertFalse($collection->contains('Brasil'));

        $findKeyAndValue = new Collection(
            [
                [
                    'product' => 'Desk',
                    'price'   => 200,
                ],
                [
                    'product' => 'Chair',
                    'price'   => 100,
                ],
            ]
        );

        $this->assertFalse($findKeyAndValue->contains('product', 'Bookcase'));
        $this->assertTrue($findKeyAndValue->contains('product', 'Desk'));
    }

    public function testMethodContainsClosure()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $collection->contains(
            function ($key, $value) {
                unset($key);
                $this->assertFalse(($value > 5));
            }
        );
    }

    public function testMethodCount()
    {
        $collection = new Collection([1, 2, 3, 4]);
        $this->assertEquals(4, $collection->count());
    }

    public function testMethodDiff()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $diff = $collection->diff([2, 4, 6, 8]);
        $result = $diff->all();
        $expected = [
            0 => 1,
            2 => 3,
            4 => 5,
        ];
        $this->assertEquals($expected, $result);
    }

    public function testMethodEach()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $collection->each(
            function ($item, $key) {
                echo $key.'=>'.$item.PHP_EOL;
            }
        );
        $this->expectOutputString(
            '0=>1
1=>2
2=>3
3=>4
4=>5
'
        );
    }


    /**
     * @SEE For the inverse of filter, see the reject method.
     */
    public function testMethodFilter()
    {
        $collection = new Collection([1, 2, 3, 4]);

        $filtered = $collection->filter(
            function ($item) {
                return $item > 2;
            }
        );

        $this->assertEquals([2 => 3, 3 => 4], $filtered->all());
    }

    public function testMethodFirst()
    {
        $collect = (new Collection([1, 2, 3, 4]))->first(
            function ($key, $value) {
                unset($key);

                return $value > 2;
            }
        );
        $this->assertEquals(3, $collect);
        $this->assertEquals(1, (new Collection([1, 2, 3, 4]))->first());
    }

    public function testMethodFlatten()
    {
        $collection = new Collection(['name' => 'taylor', 'languages' => ['php', 'javascript']]);

        $flattened = $collection->flatten();

        $this->assertEquals(['taylor', 'php', 'javascript'], $flattened->all());
    }

    public function testMethodFlip()
    {
        $collection = new Collection(['name' => 'taylor', 'framework' => 'laravel']);

        $flipped = $collection->flip();

        $this->assertEquals(['taylor' => 'name', 'laravel' => 'framework'], $flipped->all());
    }

    public function testMethodForget()
    {
        $collection = new Collection(['name' => 'taylor', 'framework' => 'laravel']);

        $collection->forget('name');

        $this->assertEquals(['framework' => 'laravel'], $collection->all());
    }

    public function testMethodForPage()
    {
        $collection = (new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9]))->forPage(2, 3);

        $this->assertEquals([4, 5, 6], $collection->all());
    }

    public function testMethodGet()
    {
        $collection = new Collection(['name' => 'taylor', 'framework' => 'laravel']);

        $this->assertEquals('taylor', $collection->get('name'));
        $this->assertEquals('laravel', $collection->get('framework'));
        $this->assertEquals(null, $collection->get('country'));
        $this->assertEquals('Brazil', $collection->get('country', 'Brazil'));

        $testClosure = $collection->get(
            'email',
            function () {
                return 'default-value';
            }
        );
        $this->assertEquals('default-value', $testClosure);
    }

    public function testMethodGroupBy()
    {
        $collection = new Collection(
            [
                [
                    'account_id' => 'account-x10',
                    'product'    => 'Chair',
                ],
                [
                    'account_id' => 'account-x10',
                    'product'    => 'Bookcase',
                ],
                [
                    'account_id' => 'account-x11',
                    'product'    => 'Desk',
                ],
            ]
        );

        $grouped = $collection->groupBy('account_id');

        $result = $grouped->toArray();
        $expected = [
            'account-x10' => [
                [
                    'account_id' => 'account-x10',
                    'product'    => 'Chair',
                ],
                [
                    'account_id' => 'account-x10',
                    'product'    => 'Bookcase',
                ],
            ],
            'account-x11' => [
                [
                    'account_id' => 'account-x11',
                    'product'    => 'Desk',
                ],
            ],
        ];
        $this->assertEquals($expected, $result);
    }

	public function testMethodHas()
    {
        $collection = new Collection(['account_id' => 1, 'product' => 'Desk']);

        $this->assertFalse($collection->has('email'));
        $this->assertTrue($collection->has('product'));
    }

    public function testMethodImplode()
    {
        $collection = new Collection(
            [
                [
                    'account_id' => 1,
                    'product'    => 'Desk',
                ],
                [
                    'account_id' => 2,
                    'product'    => 'Chair',
                ],
            ]
        );

        $result = $collection->implode('product', ', ');
        $expected = 'Desk, Chair';
        $this->assertEquals($expected, $result);
    }

	public function testMethodIntersect()
    {
        $collection = new Collection(['Desk', 'Sofa', 'Chair']);

        $intersect = $collection->intersect(['Desk', 'Chair', 'Bookcase']);

        $this->assertEquals([0 => 'Desk', 2 => 'Chair'], $intersect->all());
    }

    public function testMethodIsEmpty()
    {
        $this->assertTrue((new Collection([]))->isEmpty());
        $this->assertFalse((new Collection([1, 2, 3]))->isEmpty());
    }

    public function testMethodKeyBy()
    {
        $collection = new Collection(
            [
                [
                    'product_id' => 'prod-100',
                    'name'       => 'desk',
                ],
                [
                    'product_id' => 'prod-200',
                    'name'       => 'chair',
                ],
            ]
        );

        $keyed = $collection->keyBy('product_id');

        $result = $keyed->all();
        $expected = [
            'prod-100' => [
                'product_id' => 'prod-100',
                'name'       => 'desk',
            ],
            'prod-200' => [
                'product_id' => 'prod-200',
                'name'       => 'chair',
            ],
        ];
        $this->assertEquals($expected, $result);
    }

    public function testMethodKeys()
    {
        $collection = new Collection(
            [
                'prod-100' => [
                    'product_id' => 'prod-100',
                    'name'       => 'Desk',
                ],
                'prod-200' => [
                    'product_id' => 'prod-200',
                    'name'       => 'Chair',
                ],
            ]
        );

        $keys = $collection->keys();
        $this->assertEquals(['prod-100', 'prod-200'], $keys->all());
    }

	public function testMethodLast()
    {
        $collection = (new Collection([1, 2, 3, 4]))->last(
            function ($key, $value) {
                unset($key);

                return $value < 3;
            }
        );
        $this->assertEquals(2, $collection);
        $this->assertEquals(4, ((new Collection([1, 2, 3, 4]))->last()));
    }

    public function testMethodMap()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);

        $multiplied = $collection->map(
            function ($item, $key) {
                unset($key);

                return $item * 2;
            }
        );

        $this->assertEquals([2, 4, 6, 8, 10], $multiplied->all());
    }

    public function testMethodMerge()
    {
        $collection = new Collection(['product_id' => 1, 'name' => 'Desk']);

        $merged = $collection->merge(['price' => 100, 'discount' => false]);

        $result = $merged->all();
        $expected = [
            'product_id' => 1,
            'name'       => 'Desk',
            'price'      => 100,
            'discount'   => false,
        ];
        $this->assertEquals($expected, $result);
    }

    public function testMethodPluck()
    {
        $collection = new Collection(
            [
                [
                    'product_id' => 'prod-100',
                    'name'       => 'Desk',
                ],
                [
                    'product_id' => 'prod-200',
                    'name'       => 'Chair',
                ],
            ]
        );

        $plucked = $collection->pluck('name');

        $result = $plucked->all();
        $expected = [
            'Desk',
            'Chair',
        ];
        $this->assertEquals($expected, $result);
    }

    public function testMethodPop()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $this->assertEquals(5, $collection->pop());
        $this->assertEquals([1, 2, 3, 4], $collection->all());
    }

    public function testMethodPrepend()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);

        $collection->prepend(0);

        $this->assertEquals([0, 1, 2, 3, 4, 5], $collection->all());
    }

    public function testMethodPull()
    {
        $collection = new Collection(['product_id' => 'prod-100', 'name' => 'Desk']);

        $this->assertEquals('Desk', $collection->pull('name'));
        $this->assertEquals(['product_id' => 'prod-100'], $collection->all());
    }

    public function testMethodPush()
    {
        $collection = new Collection([1, 2, 3, 4]);

        $collection->push(5);

        $this->assertEquals([1, 2, 3, 4, 5], $collection->all());
    }

    public function testMethodPut()
    {
        $collection = new Collection(['product_id' => 1, 'name' => 'Desk']);

        $collection->put('price', 100);

        $this->assertEquals(['product_id' => 1, 'name' => 'Desk', 'price' => 100], $collection->all());
    }

    public function testMethodRandom()
    {
        $array = [
            1,
            2,
            3,
            4,
            5,
        ];
        $collection = new Collection($array);

        $this->assertTrue(in_array($collection->random(), $array));
    }

    public function testMethodReduce()
    {
        $collection = new Collection([1, 2, 3]);

        $total = $collection->reduce(
            function ($carry, $item) {
                return $carry + $item;
            }
        );
        $this->assertEquals(6, $total);
    }

    public function testMethodReject()
    {
        $collection = new Collection([1, 2, 3, 4]);

        $filtered = $collection->reject(
            function ($item) {
                return $item > 2;
            }
        );

        $this->assertEquals([1, 2], $filtered->all());
    }

    public function testMethodReverse()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);

        $reversed = $collection->reverse();

        $this->assertEquals([5, 4, 3, 2, 1], $reversed->all());
    }

	public function testMethodSearch()
    {
        $collection = new Collection([2, 4, 6, 8]);

        $this->assertEquals(1, $collection->search(4));
        $this->assertEquals(false, $collection->search('4', true));
    }

    public function testMethodShift()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $this->assertEquals(1, $collection->shift());
        $this->assertEquals([2, 3, 4, 5], $collection->all());
    }

    public function testMethodShuffle()
    {
        $array = [
            1,
            2,
            3,
            4,
            5,
        ];
        $collection = new Collection($array);
        $collection->shuffle();
        $this->assertEquals(120, array_product($collection->all()));
        $this->assertEquals($array, $collection->sort()->toArray());
    }

    public function testMethodSlice()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $slice = $collection->slice(4);

        $this->assertEquals([5, 6, 7, 8, 9, 10], $slice->all());
    }

    public function testMethodSort()
    {
        $collection = new Collection([5, 3, 1, 2, 4]);

        $sorted = $collection->sort();

        $this->assertEquals([1, 2, 3, 4, 5], $sorted->values()->all());
    }

    public function testMethodSortBy()
    {
        $collection = new Collection(
            [
                [
                    'name'  => 'Desk',
                    'price' => 200,
                ],
                [
                    'name'  => 'Chair',
                    'price' => 100,
                ],
                [
                    'name'  => 'Bookcase',
                    'price' => 150,
                ],
            ]
        );

        $sorted = $collection->sortBy('price');

        $result = $sorted->values()->all();
        $expected = [
            [
                'name'  => 'Chair',
                'price' => 100,
            ],
            [
                'name'  => 'Bookcase',
                'price' => 150,
            ],
            [
                'name'  => 'Desk',
                'price' => 200,
            ],
        ];
        $this->assertEquals($expected, $result);
    }

	public function testMethodSortByDesc()
    {
        $collection = new Collection(
            [
                [
                    'name'  => 'Desk',
                    'price' => 200,
                ],
                [
                    'name'  => 'Chair',
                    'price' => 100,
                ],
                [
                    'name'  => 'Bookcase',
                    'price' => 150,
                ],
            ]
        );

        $sorted = $collection->sortByDesc('price');

        $result = $sorted->values()->all();
        $expected = [
            [
                'name'  => 'Desk',
                'price' => 200,
            ],
            [
                'name'  => 'Bookcase',
                'price' => 150,
            ],
            [
                'name'  => 'Chair',
                'price' => 100,
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    public function testMethodSplice()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);

        $chunk = $collection->splice(2);

        $this->assertEquals([3, 4, 5], $chunk->all());

        $this->assertEquals([1, 2], $collection->all());
    }

    public function testMethodSum()
    {
        $sum = (new Collection([1, 2, 3, 4, 5]))->sum();
        $this->assertEquals(15, $sum);
    }

    public function testMethodTake()
    {
        $collection = new Collection([0, 1, 2, 3, 4, 5]);

        $chunk = $collection->take(3);

        $this->assertEquals([0, 1, 2], $chunk->all());
    }

    public function testMethodToArray()
    {
        $collection = new Collection(['name' => 'Desk', 'price' => 200]);
        $this->assertEquals(
            [
                'name'  => 'Desk',
                'price' => 200,
            ],
            $collection->toArray()
        );
    }

    public function testMethodToJason()
    {
        $collection = new Collection(['name' => 'Desk', 'price' => 200]);

        $this->assertEquals('{"name":"Desk","price":200}', $collection->toJson());
    }

    public function testMethodTransform()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);

        $collection->transform(
            function ($item, $key) {
                unset($key);

                return $item * 2;
            }
        );

        $this->assertEquals([2, 4, 6, 8, 10], $collection->all());
    }


    public function testMethodUnique()
    {
        $collection = new Collection([1, 1, 2, 2, 3, 4, 2]);

        $unique = $collection->unique();

        $this->assertEquals([1, 2, 3, 4], $unique->values()->all());
    }

    public function testMethodValues()
    {
        $collection = new Collection(
            [
                10 => [
                    'product' => 'Desk',
                    'price'   => 200,
                ],
                11 => [
                    'product' => 'Desk',
                    'price'   => 200,
                ],
            ]
        );

        $values = $collection->values();

        $this->assertEquals(
            [
                0 => [
                    'product' => 'Desk',
                    'price'   => 200,
                ],
                1 => [
                    'product' => 'Desk',
                    'price'   => 200,
                ],
            ],
            $values->all()
        );
    }

    public function testMethodWhere()
    {
        $collection = new Collection(
            [
                [
                    'product' => 'Desk',
                    'price'   => 200,
                ],
                [
                    'product' => 'Chair',
                    'price'   => 100,
                ],
                [
                    'product' => 'Bookcase',
                    'price'   => 150,
                ],
                [
                    'product' => 'Door',
                    'price'   => 100,
                ],
            ]
        );

        $filtered = $collection->where('price', 100);

        $this->assertEquals(
            [
                1 => [
                    'product' => 'Chair',
                    'price'   => 100,
                ],
                3 => [
                    'product' => 'Door',
                    'price'   => 100,
                ],
            ],
            $filtered->all()
        );
    }

    public function testMethodWhereLoose()
    {
        $collection = new Collection(
            [
                [
                    'product' => 'Desk',
                    'price'   => 200,
                ],
                [
                    'product' => 'Chair',
                    'price'   => 100,
                ],
                [
                    'product' => 'Bookcase',
                    'price'   => 150,
                ],
                [
                    'product' => 'Door',
                    'price'   => 100,
                ],
            ]
        );

        $filtered = $collection->whereLoose('price', 100);

        $this->assertEquals(
            [
                1 => [
                    'product' => 'Chair',
                    'price'   => 100,
                ],
                3 => [
                    'product' => 'Door',
                    'price'   => 100,
                ],
            ],
            $filtered->all()
        );
    }

    public function testMethodZip()
    {
        $collection = new Collection(['Chair', 'Desk']);

        $zipped = $collection->zip([100, 200]);

        $this->assertEquals([['Chair', 100], ['Desk', 200]], $zipped->toArray());
    }

    public function testMethodMax()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6]);
        $this->assertEquals(6, $collection->max());
    }

    public function testMethodMin()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6]);
        $this->assertEquals(1, $collection->min());
    }

    public function testMethodMake()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $this->assertInstanceOf('Collection\Collection', $collection);
    }

    public function testMethodFetch()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $fetch = $collection->fetch(1);
        $this->assertEquals([], $fetch->toArray());
    }

    public function testMethodFilterTryCoverage()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $filter = $collection->filter();
        $this->assertEquals($items, $filter->toArray());
    }

    public function testMethodImplodeTryCoverage()
    {
        $items = null;
        $collection = Collection::make($items);
        $result = $collection->implode(',');
        $expected = '';
        $this->assertEquals($expected, $result);
    }

    public function testMethodLists()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $list = $collection->lists(2, 1);
        $this->assertEquals(['' => null], $list->toArray());
    }

    public function testMethodRandoExpectedException()
    {
        try {
            $items = [
                1,
                2,
                3,
                4,
                5,
                6,
            ];
            $collection = Collection::make($items);
            $collection->random(556);
        } catch (Exception $ex) {
            $this->assertEquals(
                'You requested 556 items, '.'but there are only 6 items in '.'the collection',
                $ex->getMessage()
            );

            return;
        }

        $this->fail('Uma exception não foi lançada');
    }

    public function testMethodToString()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = (string) Collection::make($items);
        $this->assertEquals('[1,2,3,4,5,6]', $collection);
    }

    public function testMethodToJsonSeriable()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $result = $collection->jsonSerialize();
        $expected = $items;
        $this->assertEquals($expected, $result);
    }

    public function testMethodGetIterator()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $result = $collection->getIterator();

        $this->assertInstanceOf('ArrayIterator', $result);
    }

    public function testMethodGetCacheIterator()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $result = $collection->getCachingIterator();
        $this->assertInstanceOf('CachingIterator', $result);
    }

    public function testMethodOffSetGet()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $result = $collection->offsetGet(1);
        $expected = 2;
        $this->assertEquals($expected, $result);
    }

    public function testMethodRandomTryCoverage()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $random = $collection->random(5);
        $this->assertEquals(5, $random->count());
    }

    public function testMethodRejectTryCoverage()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $collection->reject(null);
        $this->assertEquals($items, $collection->toArray());
    }

    public function testMethodSearchTryCoverage()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $collection->search(
            function () {
                return false;
            }
        );
        $this->assertEquals($items, $collection->toArray());
    }

    public function testMethodForceCoverageEach()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $collection->each(
            function () {
                return false;
            }
        );
        $this->assertEquals($items, $collection->toArray());
    }

    public function testMethodSearchCoverageForce()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $search = $collection->search(
            function () {
                return true;
            }
        );
        $this->assertEquals(0, $search);
    }

    public function testMethodSpliceForceCoverage()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $splice = $collection->splice(1, 1, []);
        $this->assertEquals([2], $splice->toArray());
    }

    public function testMethodSumForceCoverage()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $collection->sum(
            function ($item) {
                return $item;
            }
        );
        $this->assertEquals($items, $collection->toArray());
    }

    public function testMethodTakeForceCoverage()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $collection->take(-1);
        $this->assertEquals($items, $collection->toArray());
    }

    public function testUniqueForceCoverage()
    {
        $items = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];
        $collection = Collection::make($items);
        $unique = $collection->unique(1);
        $this->assertEquals([1], $unique->toArray());
    }

    public function testForceCoverage()
    {
        $col1 = [
            1,
            2,
            3,
        ];
        $collection1 = new Collection($col1);
        $collection2 = new Collection($collection1);
        $this->assertEquals($col1, $collection2->toArray());

        $colors = new \Collection\Test\Fixture\Colors();
        $colors->setColor('red');
        $colors->setColor('blue');
        $colors->setColor('pink');

        $collection3 = new Collection($colors);
        $this->assertEquals(['red', 'blue', 'pink'], $collection3->toArray());
        $cars = new \Collection\Test\Fixture\Cars();
        $cars->setType('Sedam');
        $cars->setType('Hatch');
        $collection4 = new Collection($cars);
        $this->assertEquals('["Sedam","Hatch"]', $collection4->toJson());
    }

    public function testGetCollectionIfGetKeyIsArray()
    {
        $collection = new Collection([1 => [123]]);
        $result = $collection->get(1);
        $this->assertInstanceOf(Collection::class, $result);

        $this->assertEquals(123, $result->first());
    }

    public function testEachGetCollectionIfGetKeyIsArray()
    {
        $collection = new Collection([1 => [123]]);
        $collection->each(
            function ($item) {
                $this->assertInstanceOf(Collection::class, $item);

                $this->assertEquals(123, $item->first());
            }
        );
    }

    public function testIsArrayFalse()
    {
        $collection = new Collection([1, 2, 3]);
        $this->assertFalse(is_array($collection));
    }

    public function testIsObjectTrue()
    {
        $collection = new Collection([1, 2, 3]);
        $this->assertTrue(is_object($collection));
    }

    public function testFirstReturNewCollection()
    {
        $collection = new Collection([1 => ['first']]);
        $value = $collection->first();

        $this->assertInstanceOf(Collection::class, $value);
        $this->assertEquals('first', $value->first());
    }

    public function testLastReturNewCollection()
    {
        $collection = new Collection([1 => ['last']]);
        $value = $collection->last();

        $this->assertInstanceOf(Collection::class, $value);
        $this->assertEquals('last', $value->last());
    }

    public function testGetAndRemove()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $result = $collection->getAndRemove(2);
        $expected = 3;
        $this->assertEquals($expected, $result);
        $this->assertEquals(
            [
                0 => 1,
                1 => 2,
                3 => 4,
                4 => 5,
            ],
            $collection->toArray()
        );
    }

    public function testGetAndRemoveBooleanValueTrue()
    {
        $collection = new Collection([1, true, 3, 4, 5]);
        $result = $collection->getAndRemove(1);
        $expected = true;
        $this->assertEquals($expected, $result);
        $this->assertEquals(
            [
                0 => 1,
                2 => 3,
                3 => 4,
                4 => 5,
            ],
            $collection->toArray()
        );
    }

    public function testGetAndRemoveBooleanValueFalse()
    {
        $collection = new Collection([1, false, 3, 4, 5]);
        $result = $collection->getAndRemove(1);
        $expected = false;
        $this->assertEquals($expected, $result);
        $this->assertEquals(
            [
                0 => 1,
                2 => 3,
                3 => 4,
                4 => 5,
            ],
            $collection->toArray()
        );
    }

    public function testGetAndRemoveBooleanValueBlank()
    {
        $collection = new Collection([1, '', 3, 4, 5]);
        $result = $collection->getAndRemove(1);
        $expected = '';
        $this->assertEquals($expected, $result);
        $this->assertEquals(
            [
                0 => 1,
                2 => 3,
                3 => 4,
                4 => 5,
            ],
            $collection->toArray()
        );
    }

    public function testGetAndRemoveBooleanValueSpace()
    {
        $collection = new Collection([1, ' ', 3, 4, 5]);
        $result = $collection->getAndRemove(1);
        $expected = ' ';
        $this->assertEquals($expected, $result);
        $this->assertEquals(
            [
                0 => 1,
                2 => 3,
                3 => 4,
                4 => 5,
            ],
            $collection->toArray()
        );
    }

    public function testGetAndRemoveBooleanValueNull()
    {
        $collection = new Collection([1, null, 3, 4, 5]);
        $result = $collection->getAndRemove(1);
        $expected = null;
        $this->assertEquals($expected, $result);
        $this->assertEquals(
            [
                0 => 1,
                2 => 3,
                3 => 4,
                4 => 5,
            ],
            $collection->toArray()
        );
    }

    public function testMoveElementKeyNumeric()
    {
        $collection = new Collection(['Vasco', 'Flamengo', 'Botafogo']);
        $result = $collection->moveElement(0, 2);
        $expected = [
            'Flamengo',
            'Botafogo',
            'Vasco',
        ];
        $this->assertSame($expected, $result->toArray());
    }

    public function testMoveElementKeyString()
    {
        $collection = new Collection(
            [
                'a' => 'Vasco',
                'b' => 'Flamengo',
                'c' => 'Botafogo',
            ]
        );
        $result = $collection->moveElement('a', 1);
        $expected = [
            'b' => 'Flamengo',
            'a' => 'Vasco',
            'c' => 'Botafogo',
        ];

        $this->assertSame($expected, $result->toArray());
    }

    public function testModify()
    {
        $collection = new Collection(['srt1' => 'srt1', 'srt2' => 'srt2', 1, 'srt3' => 'srt3', 1]);

        $new = $collection->modify(
            function ($value) {
                if (is_string($value)) {
                    return $value;
                }
            }
        );

        $this->assertInstanceOf(Collection::class, $new);
        $this->assertSame(['srt1' => 'srt1', 'srt2' => 'srt2', 'srt3' => 'srt3'], $new->toArray());
    }

    public function testNoNotice()
    {
        $collection = new Collection([1, 2, 3]);
        $this->assertNull($collection[3]);
    }
}
