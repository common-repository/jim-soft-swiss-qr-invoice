<?php declare(strict_types=1);

namespace Sprain\SwissQrBill\DataGroup\Element;

use Sprain\SwissQrBill\Constraint\ValidCreditorReference;
use Sprain\SwissQrBill\DataGroup\QrCodeableInterface;
use Sprain\SwissQrBill\String\StringModifier;


final class PaymentReference implements QrCodeableInterface
{

    public const TYPE_QR = 'QRR';
    public const TYPE_SCOR = 'SCOR';
    public const TYPE_NON = 'NON';

    /**
     * Reference type
     */
    private string $type;

    /**
     * Structured reference number
     * Either a QR reference or a Creditor Reference (ISO 11649)
     */
    private ?string $reference;

    private function __construct(string $type, ?string $reference)
    {
        $this->type = $type;
        $this->reference = $reference;

        $this->handleWhiteSpaceInReference();
    }

    public static function create(string $type, ?string $reference = null): self
    {
        return new self($type, $reference);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function getFormattedReference(): ?string
    {
        switch ($this->type) {
            case self::TYPE_QR:
                return trim(strrev(chunk_split(strrev($this->reference), 5, ' ')));
            case self::TYPE_SCOR:
                return trim(chunk_split($this->reference, 4, ' '));
            default:
                return null;
        }
    }

    public function getQrCodeData(): array
    {
        return [
            $this->getType(),
            $this->getReference()
        ];
    }


    public function getGroupSequence()
    {
        return [
            'default',
            $this->getType()
        ];
    }

    private function handleWhiteSpaceInReference(): void
    {
        if (null === $this->reference) {
            return;
        }

        $this->reference = StringModifier::stripWhitespace($this->reference);

        if ('' === $this->reference) {
            $this->reference = null;
        }
    }
}
