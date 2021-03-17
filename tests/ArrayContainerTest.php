<?php

use ashrafakl\tools\arrays\ArrayContainer;
use PHPUnit\Framework\TestCase;

class ArrayContainerTest extends TestCase
{
    /**
     * Test sum, shift, unshift, pop, push and count methods
     */
    public function testOne()
    {
        // the expected sum of this array is equal to 21
        $data = [10, 2, 3, 6];
        $expected = 10 + 2 + 3 + 6;
        $array = new ArrayContainer($data);
        $this->assertEquals($expected, $array->sum());
        $array->shift();
        $expected = $expected - 10;
        $this->assertEquals($expected, $array->sum());
        $array->unshift(8);
        $expected = $expected + 8;
        $this->assertEquals($expected, $array->sum());
        $array->unshift(12, 21, 5);
        $expected = $expected + 12 + 21 + 5;
        $this->assertEquals($expected, $array->sum());
        $this->assertEquals(12, $array[0]);
        $this->assertEquals(21, $array[1]);
        $this->assertEquals(5, $array[2]);
        $array->pop();
        $expected = $expected - 6;
        $this->assertEquals($expected, $array->sum());
        $array->push(16);
        $expected = $expected + 16;
        $this->assertEquals($expected, $array->sum());
        $array->push(5, 12, -1);
        $expected = $expected + 5 + 12 + (-1);
        $this->assertEquals($expected, $array->sum());
        $this->assertEquals(5, $array[$array->count() - 3]);
        $this->assertEquals(12, $array[$array->count() - 2]);
        $this->assertEquals(-1, $array[$array->count() - 1]);
    }

    /**
     * Test map, filter, getList, values and clone methods
     */
    public function testTwo()
    {
        $data = [0, 1, 2, 3, 4];
        $expected = [1, 2, 4, 8, 16];
        $array = (new ArrayContainer($data))
            ->map(function ($val) {
                return pow(2, $val);
            });
        $this->assertEquals($expected, $array->getList());
        $expected = [8, 16];
        $array2 = $array->clone();
        $array->filter(function ($val) {
            return $val > 4;
        });
        $array2->filter(function ($val) {
            return $val > 4;
        }, true);
        $this->assertNotEquals($expected, $array->getList());
        $this->assertEquals($expected, $array2->getList());
    }

    /**
     * Test reverse, foreach, keys, product, order, printR and __toString methods
     */
    public function testThree()
    {
        $data = [19, 10, 5, 8, 9];
        $array = new ArrayContainer($data);
        $expected = [9, 8, 5, 10, 19];
        $list = $array->clone()->reverse(true)->getList();
        $this->assertEquals($expected, $array->reverse()->getList());
        $this->assertEquals(9, $list[4]);
        $this->assertEquals(5, $list[2]);
        $expected = [
            'value' => 19 + 10 + 5 + 8 + 9,
            'index' => 0 + 1 + 2 + 3 + 4,
        ];
        $actual = [
            'value' => 0,
            'index' => 0,
        ];
        $array->forEach(function ($value, $index) use (&$actual) {
            $actual['value'] += $value;
            $actual['index'] += $index;
        });
        $this->assertEquals($expected['value'], $actual['value']);
        $this->assertEquals($expected['index'], $actual['index']);

        $expected = [
            'value' => 9 + 8 + 5,
            'index' => 0 + 1 + 2,
        ];
        $actual = [
            'value' => 0,
            'index' => 0,
        ];
        $array->forEach(function ($value, $index) use (&$actual) {
            $actual['value'] += $value;
            $actual['index'] += $index;
            if ($index == 2) {
                return false;
            }
        });
        $this->assertEquals($expected['value'], $actual['value']);
        $this->assertEquals($expected['index'], $actual['index']);

        $expected = [0, 1, 2, 3, 4];
        $this->assertEquals($expected, $array->keys());
        $sum =$array->reduce(function ($carry, $item) {
            return $carry + $item;
        });
        $expected = 19 + 10 + 5 + 8 + 9;
        $this->assertEquals($expected, $sum);
        $expected = 19 + 10 + 5 + 8 + 9 + 100;
        $sum =$array->reduce(function ($carry, $item) {
            return $carry + $item;
        }, 100);
        $this->assertEquals($expected, $sum);
        $expected =  19 * 10 * 5 * 8 * 9;
        $this->assertEquals($expected, $array->product());
        $expected = [19, 10, 9, 8, 5];
        $array->order(function ($list) {
            array_multisort($list, SORT_DESC, SORT_NUMERIC);
            return $list;
        });
        $this->assertEquals($expected, $array->getList());
        $expected = print_r($array->getList(), true);
        $this->assertEquals($expected, $array->printR(true));
        $this->assertEquals($expected, $array->__toString());
    }

    /**
     * Test ArrayAccess methods
     */
    public function testFour()
    {
        $data = [19, 10, 5, 8, 9];
        $array = new ArrayContainer($data);
        $this->assertEquals(19, $array[0]);
        $array[0] = 100;
        $this->assertEquals(100, $array[0]);
        $array[] = 150;
        $this->assertEquals(150, $array[$array->count() - 1]);
        unset($array[0]);
        $this->assertArrayNotHasKey(0, $array);
    }

    /**
     * Test getGenerator and chunk methods
     */
    public function testFive()
    {
        $data = [1, 10, 4, 6, 9, 19, 50, 12];
        $array = new ArrayContainer($data);
        $this->assertTrue($array->getGenerator() instanceof Generator);
        $chunks = $array->chunk(3);
        $expected = [
            [1 , 10 , 4],
            [6 , 9 , 19],
            [50 , 12]
        ];
        $this->assertEquals($expected[0], $chunks[0]);
        $this->assertEquals($expected[1], $chunks[1]);
        $this->assertEquals($expected[2], $chunks[2]);
        $chunks = $array->chunk(3, true);
        $this->assertArrayHasKey(3, $chunks[1]);
        $this->assertArrayHasKey(4, $chunks[1]);
        $this->assertArrayHasKey(5, $chunks[1]);
        $this->assertArrayHasKey(6, $chunks[2]);
        $this->assertArrayHasKey(7, $chunks[2]);
    }
}
