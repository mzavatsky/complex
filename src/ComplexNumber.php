<?php

declare(strict_types=1);

namespace Mzavatsky\Complex;

class ComplexNumber
{
    private function __construct(private float $re, private float $im)
    {
    }

    public static function reIm(float $re, float $im): self
    {
        return new self($re, $im);
    }

    public static function re(float $re): self
    {
        return new self($re, 0);
    }

    public static function im(float $im): self
    {
        return new self(0, $im);
    }

    public static function zero(): self
    {
        return new self(re: 0, im: 0);
    }

    public function getRe(): float
    {
        return $this->re;
    }

    public function getIm(): float
    {
        return $this->im;
    }

    public function eq(self $op): bool
    {
        return $this->getRe() == $op->getRe() && $this->getIm() == $op->getIm();
    }

    public function add(self $op): self
    {
        return new self(
            re: $this->getRe() + $op->getRe(),
            im: $this->getIm() + $op->getIm(),
        );
    }

    public function sub(self $op): self
    {
        return new self(
            re: $this->getRe() - $op->getRe(),
            im: $this->getIm() - $op->getIm(),
        );
    }

    public function mul(self $op): self
    {
        return new self(
            re: $this->getRe() * $op->getRe() - $this->getIm() * $op->getIm(),
            im: $this->getRe() * $op->getIm() + $this->getIm() * $op->getRe(),
        );
    }

    public function div(self $op): self
    {
        if ($op->eq(self::zero())) {
            throw new \InvalidArgumentException('Division by zero');
        }

        $denom = $op->getRe() * $op->getRe() + $op->getIm() * $op->getIm();
        $opInverse = new self(
            re: $op->getRe() / $denom,
            im: - $op->getIm() / $denom
        );

        return $this->mul($opInverse);
    }

    public function __toString(): string
    {
        $tokens = [];

        if ($this->getRe() !== 0.0) {
            $tokens[] = sprintf('%s', $this->getRe());
        }

        if ($this->getIm() !== 0.0) {
            if ($tokens === []) {
                $tokens[] = sprintf('%s * i', $this->getIm());
            } else {
                $tokens[] = $this->getIm() > 0 ? '+' : '-';
                $tokens[] = sprintf('%s * i', abs($this->getIm()));
            }
        }

        return $tokens === [] ? '0' : implode(' ', $tokens);
    }
}