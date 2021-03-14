<?php

namespace tests;

use ashrafakl\tools\arrays\ArrayContainer;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ashrafakl\tools\arrays\ArrayContainer
 */
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
        Assert::assertEquals($expected, $array->sum());
        $array->shift();
        $expected = $expected - 10;
        Assert::assertEquals($expected, $array->sum());
        $array->unshift(8);
        $expected = $expected + 8;
        Assert::assertEquals($expected, $array->sum());
        $array->unshift(12, 21, 5);
        $expected = $expected + 12 + 21 + 5;
        Assert::assertEquals($expected, $array->sum());
        Assert::assertEquals(12, $array[0]);
        Assert::assertEquals(21, $array[1]);
        Assert::assertEquals(5, $array[2]);
        $array->pop();
        $expected = $expected - 6;
        Assert::assertEquals($expected, $array->sum());
        $array->push(16);
        $expected = $expected + 16;
        Assert::assertEquals($expected, $array->sum());
        $array->push(5, 12, -1);
        $expected = $expected + 5 + 12 + (-1);
        Assert::assertEquals($expected, $array->sum());
        Assert::assertEquals(5, $array[$array->count() - 3]);
        Assert::assertEquals(12, $array[$array->count() - 2]);
        Assert::assertEquals(-1, $array[$array->count() - 1]);
    }

    /**
     * Test map, filter, getList and clone methods
     */
    public function testTwo()
    {
        $data = [0, 1, 2, 3, 4];
        $expected = [1, 2, 4, 8, 16];
        $array = (new ArrayContainer($data))
            ->map(function ($val) {
                return pow(2, $val);
            });
        Assert::assertEquals($expected, $array->getList());
        $expected = [8, 16];
        $array2 = $array->clone();
        $array->filter(function ($val) {
            return $val > 4;
        });
        $array2->filter(function ($val) {
            return $val > 4;
        }, true);
        Assert::assertNotEquals($expected, $array->getList());
        Assert::assertEquals($expected, $array2->getList());
    }
}
