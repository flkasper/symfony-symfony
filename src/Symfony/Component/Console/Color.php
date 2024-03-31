<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console;

use Symfony\Component\Console\Exception\InvalidArgumentException;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class Color
{
    public const BLACK = 'black';
    public const RED = 'red';
    public const GREEN = 'green';
    public const YELLOW = 'yellow';
    public const BLUE = 'blue';
    public const MAGENTA = 'magenta';
    public const CYAN = 'cyan';
    public const WHITE = 'white';
    public const DEFAULT = 'default';
    public const GRAY = 'gray';
    public const BRIGHT_RED = 'bright-red';
    public const BRIGHT_GREEN = 'bright-green';
    public const BRIGHT_YELLOW = 'bright-yellow';
    public const BRIGHT_BLUE = 'bright-blue';
    public const BRIGHT_MAGENTA = 'bright-magenta';
    public const BRIGHT_CYAN = 'bright-cyan';
    public const BRIGHT_WHITE = 'bright-white';
    public const OPTION_BOLD = 'bold';
    public const OPTION_UNDERSCORE = 'underscore';
    public const OPTION_BLINK = 'blink';
    public const OPTION_REVERSE = 'reverse';
    public const OPTION_CONCEAL = 'conceal';

    private const AVAILABLE_COLORS = [
        self::BLACK => 0,
        self::RED => 1,
        self::GREEN => 2,
        self::YELLOW => 3,
        self::BLUE => 4,
        self::MAGENTA => 5,
        self::CYAN => 6,
        self::WHITE => 7,
        self::DEFAULT => 9,
    ];
    private const AVAILABLE_BRIGHT_COLORS = [
        self::GRAY => 0,
        self::BRIGHT_RED => 1,
        self::BRIGHT_GREEN => 2,
        self::BRIGHT_YELLOW => 3,
        self::BRIGHT_BLUE => 4,
        self::BRIGHT_MAGENTA => 5,
        self::BRIGHT_CYAN => 6,
        self::BRIGHT_WHITE => 7,
    ];
    private const AVAILABLE_OPTIONS = [
        self::OPTION_BOLD => ['set' => 1, 'unset' => 22],
        self::OPTION_UNDERSCORE => ['set' => 4, 'unset' => 24],
        self::OPTION_BLINK => ['set' => 5, 'unset' => 25],
        self::OPTION_REVERSE => ['set' => 7, 'unset' => 27],
        self::OPTION_CONCEAL => ['set' => 8, 'unset' => 28],
    ];

    private string $foreground;
    private string $background;
    private array $options = [];

    public function __construct(?string $foreground = null, ?string $background = null, array $options = [])
    {
        $this->foreground = $this->parseColor($foreground ?? '');
        $this->background = $this->parseColor($background ?? '', true);

        foreach ($options as $option) {
            if (!isset(self::AVAILABLE_OPTIONS[$option])) {
                throw new InvalidArgumentException(sprintf('Invalid option specified: "%s". Expected one of (%s).', $option, implode(', ', array_keys(self::AVAILABLE_OPTIONS))));
            }

            $this->options[$option] = self::AVAILABLE_OPTIONS[$option];
        }
    }

    public function apply(string $text): string
    {
        return $this->set().$text.$this->unset();
    }

    public function set(): string
    {
        $setCodes = [];
        if ('' !== $this->foreground) {
            $setCodes[] = $this->foreground;
        }
        if ('' !== $this->background) {
            $setCodes[] = $this->background;
        }
        foreach ($this->options as $option) {
            $setCodes[] = $option['set'];
        }
        if (0 === \count($setCodes)) {
            return '';
        }

        return sprintf("\033[%sm", implode(';', $setCodes));
    }

    public function unset(): string
    {
        $unsetCodes = [];
        if ('' !== $this->foreground) {
            $unsetCodes[] = 39;
        }
        if ('' !== $this->background) {
            $unsetCodes[] = 49;
        }
        foreach ($this->options as $option) {
            $unsetCodes[] = $option['unset'];
        }
        if (0 === \count($unsetCodes)) {
            return '';
        }

        return sprintf("\033[%sm", implode(';', $unsetCodes));
    }

    private function parseColor(string $color, bool $background = false): string
    {
        if ('' === $color) {
            return '';
        }

        if ('#' === $color[0]) {
            return ($background ? '4' : '3').Terminal::getColorMode()->convertFromHexToAnsiColorCode($color);
        }

        if (isset(self::AVAILABLE_COLORS[$color])) {
            return ($background ? '4' : '3').self::AVAILABLE_COLORS[$color];
        }

        if (isset(self::AVAILABLE_BRIGHT_COLORS[$color])) {
            return ($background ? '10' : '9').self::AVAILABLE_BRIGHT_COLORS[$color];
        }

        throw new InvalidArgumentException(sprintf('Invalid "%s" color; expected one of (%s).', $color, implode(', ', array_merge(array_keys(self::AVAILABLE_COLORS), array_keys(self::AVAILABLE_BRIGHT_COLORS)))));
    }
}
