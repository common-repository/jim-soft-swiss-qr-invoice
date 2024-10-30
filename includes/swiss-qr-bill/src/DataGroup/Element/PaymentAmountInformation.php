<?php declare(strict_types=1);

namespace Sprain\SwissQrBill\DataGroup\Element;

use Sprain\SwissQrBill\DataGroup\QrCodeableInterface;


final class PaymentAmountInformation implements QrCodeableInterface
{

    public const CURRENCY_CHF = 'CHF';
    public const CURRENCY_EUR = 'EUR';

    /**
     * The payment amount due
     */
    private ?float $amount;

    /**
     * Payment currency code (ISO 4217)
     */
    private string $currency;

    private function __construct(string $currency, ?float $amount)
    {
        $this->currency = strtoupper($currency);
        $this->amount = $amount;
    }

    public static function create(string $currency, ?float $amount = null): self
    {
        return new self($currency, $amount);
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function getFormattedAmount(): ?string
    {
        if (null === $this->amount) {
            return '';
        }

        return number_format(
            $this->amount,
            2,
            '.',
            ' '
        );
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getQrCodeData(): array
    {
        if (null !== $this->getAmount()) {
            $amountOutput = number_format($this->getAmount(), 2, '.', '');
        } else {
            $amountOutput = null;
        }

        return [
            $amountOutput,
            $this->getCurrency()
        ];
    }
}
