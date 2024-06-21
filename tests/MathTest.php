<?php

use Arris\Helpers\Math;

class MathTest  extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     * @testdox Тест toRange
     */
    public function toRange()
    {
        $this->assertEquals(5, Math::toRange(5, 1, 100 ), '1');
        $this->assertEquals(1, Math::toRange(0, 1, 100 ), '2');
        $this->assertEquals(100, Math::toRange(123, 1, 100 ), '3');

        $this->assertEquals(0, Math::toRange(0, -1, 9 ));
        $this->assertEquals(-1, Math::toRange(-11, -1, 9 ));
        $this->assertEquals(9, Math::toRange(55, -1, 9 ));

        $this->assertEquals(-10, Math::toRange(-55, -10, -5 ));
        $this->assertEquals(-8, Math::toRange(-8, -10, -5 ));
        $this->assertEquals(-5, Math::toRange(55, -10, -5 ));
    }

    /**
     * @return void
     * @testdox [1.. 5? ..100]
     */
    public function t1()
    {
        $this->assertEquals(5, Math::toRange(5, 1, 100 ), '1');
    }

    /**
     * @return void
     * @testdox [0? 1...100]
     */
    public function t2()
    {
        $this->assertEquals(1, Math::toRange(0, 1, 100 ), '2');
    }

    /**
     * @return void
     * @testdox [1...100 123?]
     */
    public function t3()
    {
        $this->assertEquals(100, Math::toRange(123, 1, 100 ), '3');
    }

    /**
     * @return void
     * @testdox [-1... ?0  ...9]
     */
    public function t4()
    {
        $this->assertEquals(0, Math::toRange(0, -1, 9 ));
    }

    /**
     * @return void
     * @testdox [?-11  -1 ... +9]
     */
    public function t5()
    {
        $this->assertEquals(-1, Math::toRange(-11, -1, 9 ));
    }

    /**
     * @return void
     * @testdox [-1...9   ?55]
     */
    public function t6()
    {
        $this->assertEquals(9, Math::toRange(55, -1, 9 ));
    }

    /**
     * @return void
     * @testdox 7
     */
    public function t7()
    {
        $this->assertEquals(-10, Math::toRange(-55, -10, -5 ));
    }

    /**
     * @return void
     * @testdox 8
     */
    public function t8()
    {
        $this->assertEquals(-8, Math::toRange(-8, -10, -5 ));
    }

    /**
     * @return void
     * @testdox 9
     */
    public function t9()
    {
        $this->assertEquals(-5, Math::toRange(55, -10, -5 ));
    }

}
