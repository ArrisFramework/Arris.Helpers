<?php

use Arris\Helpers\Strings;

class HelpersTest extends PHPUnit_Framework_TestCase
{
    function test_mb_ucwords()
    {
        $this->assertEquals('Åäö', Strings::mb_ucwords('åäö'));
        $this->assertEquals('Åäö Öäå', Strings::mb_ucwords('åäö öäå'));

        $this->assertEquals(ucwords('H.G. Wells'), Strings::mb_ucwords('H.G. Wells'));
        $this->assertEquals(ucwords('h.g. wells'), Strings::mb_ucwords('h.g. wells'));
        $this->assertEquals(ucwords('H.G. WELLS'), Strings::mb_ucwords('H.G. WELLS'));
    }

    function test_mb_ucfirst()
    {
        $this->assertEquals('Åäö', Strings::mb_ucfirst('åäö'));
        $this->assertEquals('Åäö öäå', Strings::mb_ucfirst('åäö öäå'));

        $this->assertEquals(ucfirst('H.G. Wells'), Strings::mb_ucfirst('H.G. Wells'));
        $this->assertEquals(ucfirst('h.g. wells'), Strings::mb_ucfirst('h.g. wells'));
        $this->assertEquals(ucfirst('H.G. WELLS'), Strings::mb_ucfirst('H.G. WELLS'));
    }

    function test_mb_strrev()
    {
        $this->assertEquals('öäå', Strings::mb_strrev('åäö'));
        $this->assertEquals('öäÅ', Strings::mb_strrev('Åäö'));

        $this->assertEquals(strrev('bobby'), Strings::mb_strrev('bobby'));
    }

    function test_mb_str_pad()
    {
        $this->assertEquals('a   ', Strings::mb_str_pad('a', 4));
        $this->assertEquals('ö   ', Strings::mb_str_pad('ö', 4));

        $this->assertEquals(str_pad('a', 4), Strings::mb_str_pad('a', 4));
    }

    function test_mb_count_chars()
    {
        $this->assertEquals(array('ö' => 1, 'b' => 2), Strings::mb_count_chars('böb', 1));
        $this->assertEquals('bö', Strings::mb_count_chars('böb', 3));
        $this->assertEquals(count_chars('bobby', 3), count_chars('bobby', 3));
    }

    /**
     * @expectedException Exception
     */
    function test_mb_count_chars_unsupported_mode()
    {
        Strings::mb_count_chars('böb', 2);
    }

    function test_mb_str_split()
    {
        $this->assertEquals(array('b', 'ö', 'b'), Strings::mb_str_split('böb'));

        $this->assertEquals(array('bö', 'b'), Strings::mb_str_split('böb', 2));

        for ($i = 1; $i < 10; $i++) {
            $this->assertEquals(str_split('bob', $i), Strings::mb_str_split('bob', $i));
        }

        $this->assertEquals(str_split('bobby'), Strings::mb_str_split('bobby'));
        $this->assertEquals(str_split(''), Strings::mb_str_split(''));

        foreach(array(0,-1) as $length) {
            $exception_thrown = false;
            try {
                Strings::mb_str_split('foo', $length);
            } catch (Exception $e) {
                $exception_thrown = true;
                $this->assertEquals('The length of each segment must be greater than zero', $e->getMessage());
            }
            $this->assertTrue($exception_thrown);
        }
    }

}
