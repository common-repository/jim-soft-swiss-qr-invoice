<?php declare(strict_types=1);

namespace Sprain\SwissQrBill\DataGroup\Element;

use Sprain\SwissQrBill\DataGroup\QrCodeableInterface;
use Sprain\SwissQrBill\String\StringModifier;

final class CreditorInformation implements QrCodeableInterface
{
    /**
     * IBAN or QR-IBAN of the creditor
     */
    private string $iban;

    private function __construct(string $iban)
    {
        $this->iban = StringModifier::stripWhitespace($iban);
    }

    public static function create(string $iban): self
    {
        return new self($iban);
    }

    public function getIban(): string
    {
        return $this->iban;
    }

    public function getFormattedIban(): string
    {
        return trim(chunk_split($this->iban, 4, ' '));
    }

    public function containsQrIban(): bool
    {
        $qrIid = substr($this->iban, 4, 5);

        if ($this->isValid() && (int) $qrIid >= 30000 && (int) $qrIid <= 31999) {
            return true;
        }

        return false;
    }

    public function getQrCodeData(): array
    {
        return [
            $this->getIban()
        ];
    }

}
