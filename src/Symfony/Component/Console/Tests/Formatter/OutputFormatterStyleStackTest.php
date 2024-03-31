<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Tests\Formatter;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Color;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Formatter\OutputFormatterStyleStack;

class OutputFormatterStyleStackTest extends TestCase
{
    public function testPush()
    {
        $stack = new OutputFormatterStyleStack();
        $stack->push($s1 = new OutputFormatterStyle(Color::WHITE, Color::BLACK));
        $stack->push($s2 = new OutputFormatterStyle(Color::YELLOW, Color::BLUE));

        $this->assertEquals($s2, $stack->getCurrent());

        $stack->push($s3 = new OutputFormatterStyle(Color::GREEN, Color::RED));

        $this->assertEquals($s3, $stack->getCurrent());
    }

    public function testPop()
    {
        $stack = new OutputFormatterStyleStack();
        $stack->push($s1 = new OutputFormatterStyle(Color::WHITE, Color::BLACK));
        $stack->push($s2 = new OutputFormatterStyle(Color::YELLOW, Color::BLUE));

        $this->assertEquals($s2, $stack->pop());
        $this->assertEquals($s1, $stack->pop());
    }

    public function testPopEmpty()
    {
        $stack = new OutputFormatterStyleStack();
        $style = new OutputFormatterStyle();

        $this->assertEquals($style, $stack->pop());
    }

    public function testPopNotLast()
    {
        $stack = new OutputFormatterStyleStack();
        $stack->push($s1 = new OutputFormatterStyle(Color::WHITE, Color::BLACK));
        $stack->push($s2 = new OutputFormatterStyle(Color::YELLOW, Color::BLUE));
        $stack->push($s3 = new OutputFormatterStyle(Color::GREEN, Color::RED));

        $this->assertEquals($s2, $stack->pop($s2));
        $this->assertEquals($s1, $stack->pop());
    }

    public function testInvalidPop()
    {
        $stack = new OutputFormatterStyleStack();
        $stack->push(new OutputFormatterStyle(Color::WHITE, Color::BLACK));

        $this->expectException(\InvalidArgumentException::class);

        $stack->pop(new OutputFormatterStyle(Color::YELLOW, Color::BLUE));
    }
}
