<?php

declare(strict_types=1);

use Mzavatsky\Complex\ComplexNumber;
use PHPUnit\Framework\TestCase;

class ComplexNumberTest extends TestCase
{
    /**
     * @dataProvider toStringCasesProvider
     */
    public function testToString(ComplexNumber $op, string $expected): void
    {
        self::assertEquals($expected, (string)$op);
    }

    public function toStringCasesProvider(): iterable
    {
        yield [ComplexNumber::zero(), '0'];
        yield [ComplexNumber::re(15), '15'];
        yield [ComplexNumber::re(-6.25), '-6.25'];
        yield [ComplexNumber::im(33), '33 * i'];
        yield [ComplexNumber::im(-5.5), '-5.5 * i'];
        yield [ComplexNumber::reIm(2.718, 3.141592653), '2.718 + 3.141592653 * i'];
        yield [ComplexNumber::reIm(-2.718, -3.14), '-2.718 - 3.14 * i'];
    }

    public function testEquals(): void
    {
        self::assertTrue(ComplexNumber::reIm(15.3, 7.4)->eq(ComplexNumber::reIm(15.3, 7.4)));
        self::assertFalse(ComplexNumber::reIm(15.3, 7.4)->eq(ComplexNumber::reIm(15.3, -7.4)));
    }

    /**
     * @dataProvider additionCasesProvider
     */
    public function testAdd(ComplexNumber $opLeft, ComplexNumber $opRight, ComplexNumber $expected): void
    {
        $actual = $opLeft->add($opRight);
        self::assertTrue(
            $expected->eq($actual),
            sprintf('(%s) + (%s) = (%s) != (%s)', $opLeft, $opRight, $actual, $expected),
        );

        $actual = $opRight->add($opLeft);
        self::assertTrue(
            $expected->eq($actual),
            sprintf('(%s) + (%s) = (%s) != (%s)', $opRight, $opLeft, $actual, $expected),
        );
    }

    public function additionCasesProvider(): iterable
    {
        yield [ComplexNumber::zero(), ComplexNumber::reIm(2.3, 4.5), ComplexNumber::reIm(2.3, 4.5)];
        yield [ComplexNumber::reIm(3.2, 3.3), ComplexNumber::reIm(-3.2, -3.3), ComplexNumber::zero()];
        yield [ComplexNumber::re(5), ComplexNumber::im(8), ComplexNumber::reIm(5, 8)];
        yield [ComplexNumber::reIm(1, -1), ComplexNumber::reIm(-5, 8), ComplexNumber::reIm(-4, 7)];
    }

    /**
     * @dataProvider subtractionCasesProvider
     */
    public function testSub(ComplexNumber $opLeft, ComplexNumber $opRight, ComplexNumber $expected): void
    {
        $actual = $opLeft->sub($opRight);
        self::assertTrue(
            $expected->eq($actual),
            sprintf('(%s) - (%s) = (%s) != (%s)', $opLeft, $opRight, $actual, $expected),
        );
    }

    public function subtractionCasesProvider(): iterable
    {
        yield [ComplexNumber::zero(), ComplexNumber::reIm(2.3, 4.5), ComplexNumber::reIm(-2.3, -4.5)];
        yield [ComplexNumber::reIm(3.2, 3.3), ComplexNumber::reIm(-3.2, -3.3), ComplexNumber::reIm(6.4, 6.6)];
        yield [ComplexNumber::re(5), ComplexNumber::im(8), ComplexNumber::reIm(5, -8)];
        yield [ComplexNumber::reIm(1, -1), ComplexNumber::reIm(-5, 8), ComplexNumber::reIm(6, -9)];
    }

    /**
     * @dataProvider multiplicationCasesProvider
     */
    public function testMul(ComplexNumber $opLeft, ComplexNumber $opRight, ComplexNumber $expected): void
    {
        $actual = $opLeft->mul($opRight);
        self::assertTrue(
            $expected->eq($actual),
            sprintf('(%s) * (%s) = (%s) != (%s)', $opLeft, $opRight, $actual, $expected),
        );

        $actual = $opRight->mul($opLeft);
        self::assertTrue(
            $expected->eq($actual),
            sprintf('(%s) * (%s) = (%s) != (%s)', $opRight, $opLeft, $actual, $expected),
        );
    }

    public function multiplicationCasesProvider(): iterable
    {
        yield [ComplexNumber::zero(), ComplexNumber::reIm(2.3, 4.5), ComplexNumber::zero()];
        yield [
            ComplexNumber::reIm(3.2, 3.3),
            ComplexNumber::reIm(-3.2, -3.3),
            ComplexNumber::reIm(-3.2 * 3.2 + 3.3 * 3.3, -3.2 * 3.3 - 3.3 * 3.2),
        ];
        yield [ComplexNumber::re(5), ComplexNumber::im(8), ComplexNumber::im(40)];
        yield [
            ComplexNumber::reIm(1, -1), 
            ComplexNumber::reIm(-5, 8), 
            ComplexNumber::reIm(-1 * 5 + 1 * 8, 1 * 8 + 1 * 5),
        ];
    }

    /**
     * @dataProvider divisionCasesProvider
     */
    public function testDiv(ComplexNumber $opLeft, ComplexNumber $opRight, ComplexNumber $expected): void
    {
        $actual = $opLeft->div($opRight);
        self::assertTrue(
            $expected->eq($actual),
            sprintf('(%s) / (%s) = (%s) != (%s)', $opLeft, $opRight, $actual, $expected),
        );
    }

    public function divisionCasesProvider(): iterable
    {
        yield [ComplexNumber::zero(), ComplexNumber::reIm(2.3, 4.5), ComplexNumber::zero()];
        yield [ComplexNumber::reIm(13, 1), ComplexNumber::reIm(7, -6), ComplexNumber::reIm(1, 1)];
        yield [ComplexNumber::reIm(2, 4), ComplexNumber::reIm(-1, 3), ComplexNumber::reIm(1, -1)];
    }    

    public function testDivisionByZero(): void
    {
        $this->expectExceptionObject(
            new \InvalidArgumentException('Division by zero')
        );

        ComplexNumber::reIm(2, 3)->div(ComplexNumber::zero());
    }
}