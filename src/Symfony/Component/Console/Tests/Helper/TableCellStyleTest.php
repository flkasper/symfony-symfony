<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Tests\Helper;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Color;
use Symfony\Component\Console\Helper\TableCellStyle;

class TableCellStyleTest extends TestCase
{
    public function testCreateTableCellStyle()
    {
        $tableCellStyle = new TableCellStyle(['fg' => Color::RED]);
        $this->assertEquals(Color::RED, $tableCellStyle->getOptions()['fg']);

        $this->expectException(\Symfony\Component\Console\Exception\InvalidArgumentException::class);
        new TableCellStyle(['wrong_key' => null]);
    }
}
