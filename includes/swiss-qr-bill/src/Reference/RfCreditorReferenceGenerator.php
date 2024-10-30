<?php declare(strict_types=1);

namespace Sprain\SwissQrBill\Reference;

use kmukku\phpIso11649\phpIso11649;
use Sprain\SwissQrBill\String\StringModifier;

final class RfCreditorReferenceGenerator
{

    private string $reference;

    public static function generate(string $reference): string
    {
        $generator = new self($reference);

        return $generator->doGenerate();
    }

    public function __construct(string $reference)
    {
        $this->reference = StringModifier::stripWhitespace($reference);
    }

    public function doGenerate(): string
    {

        $generator = new phpIso11649();

        return $generator->generateRfReference($this->reference, false);
    }
}
