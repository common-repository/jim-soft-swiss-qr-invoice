<?php declare(strict_types=1);

namespace Sprain\SwissQrBill\Reference;

use Sprain\SwissQrBill\String\StringModifier;


final class QrPaymentReferenceGenerator
{

    private ?string $customerIdentificationNumber = null;
    private string $referenceNumber;

    public static function generate(?string $customerIdentificationNumber, string $referenceNumber): string
    {
        $qrPaymentReferenceGenerator = new self($customerIdentificationNumber, $referenceNumber);

        return $qrPaymentReferenceGenerator->doGenerate();
    }

    public function __construct(?string $customerIdentificationNumber, string $referenceNumber)
    {
        if (null !== $customerIdentificationNumber) {
            $this->customerIdentificationNumber = StringModifier::stripWhitespace($customerIdentificationNumber);
        }
        $this->referenceNumber = StringModifier::stripWhitespace($referenceNumber);
    }

    public function getCustomerIdentificationNumber(): ?string
    {
        return $this->customerIdentificationNumber;
    }

    public function getReferenceNumber(): ?string
    {
        return $this->referenceNumber;
    }

    public function doGenerate(): string
    {
        $completeReferenceNumber  = $this->getCustomerIdentificationNumber();

        $strlen = $completeReferenceNumber ? strlen($completeReferenceNumber) : 0;
        $completeReferenceNumber .= str_pad($this->getReferenceNumber(), 26 - $strlen, '0', STR_PAD_LEFT);
        $completeReferenceNumber .= $this->modulo10($completeReferenceNumber);

        return $completeReferenceNumber;
    }

    private function modulo10(string $number): int
    {
        $table = [0, 9, 4, 6, 8, 2, 7, 1, 3, 5];
        $next = 0;
        for ($i = 0; $i < strlen($number); $i++) {
            $next =  $table[($next + intval(substr($number, $i, 1))) % 10];
        }

        return (10 - $next) % 10;
    }
}
