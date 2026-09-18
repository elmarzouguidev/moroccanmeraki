<?php

namespace App\ValueObjects;

use App\Enums\Utilities\ConversionCurrencyType;
use App\Enums\Utilities\CurrencyType;

class Money
{
    public function __construct(
        public readonly int|float $amount,
        public readonly ConversionCurrencyType $currency
    ) {}

    public function format(bool $showCode = false): string
    {
        return $this->currency->format($this->amount, $showCode);
    }

    public function formatLocalized(?string $locale = null): string
    {
        return $this->currency->formatLocalized($this->amount, $locale);
    }

    public function convertTo(ConversionCurrencyType $targetCurrency): self
    {
        $converted = currency()?->convert($this->amount, $this->currency, $targetCurrency);
        return new self($converted, $targetCurrency);
    }

    public function add(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            $other = $other->convertTo($this->currency);
        }
        return new self($this->amount + $other->amount, $this->currency);
    }

    public function subtract(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            $other = $other->convertTo($this->currency);
        }
        return new self($this->amount - $other->amount, $this->currency);
    }

    public function multiply(int|float $factor): self
    {
        return new self($this->amount * $factor, $this->currency);
    }

    public function divide(int|float $divisor): self
    {
        if ($divisor == 0) {
            throw new \InvalidArgumentException('Cannot divide by zero');
        }
        return new self($this->amount / $divisor, $this->currency);
    }

    public function isGreaterThan(Money $other): bool
    {
        if ($this->currency !== $other->currency) {
            $other = $other->convertTo($this->currency);
        }
        return $this->amount > $other->amount;
    }

    public function isLessThan(Money $other): bool
    {
        if ($this->currency !== $other->currency) {
            $other = $other->convertTo($this->currency);
        }
        return $this->amount < $other->amount;
    }

    public function equals(Money $other): bool
    {
        if ($this->currency !== $other->currency) {
            $other = $other->convertTo($this->currency);
        }
        return abs($this->amount - $other->amount) < 0.01;
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency->value,
            'formatted' => $this->format(),
            'symbol' => $this->currency->getSymbol(),
        ];
    }

    public function __toString(): string
    {
        return $this->format();
    }
}