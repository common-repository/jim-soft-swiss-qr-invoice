<?php declare(strict_types=1);

namespace Sprain\SwissQrBill\DataGroup\Element;

use Sprain\SwissQrBill\DataGroup\AddressInterface;
use Sprain\SwissQrBill\DataGroup\QrCodeableInterface;


class CombinedAddress implements AddressInterface, QrCodeableInterface
{

    public const ADDRESS_TYPE = 'K';

    /**
     * Name or company
     */
    private string $name;

    /**
     * Address line 1
     *
     * Street and building number or P.O. Box
     */
    private ?string $addressLine1;

    /**
     * Address line 2
     *
     * Postal code and town
     */
    private string $addressLine2;

    /**
     * Country (ISO 3166-1 alpha-2)
     */
    private string $country;

    private function __construct(
        string $name,
        ?string $addressLine1,
        string $addressLine2,
        string $country
    ) {
        $this->name = $name;
        $this->addressLine1 = $addressLine1;
        $this->addressLine2 = $addressLine2;
        $this->country = strtoupper($country);
    }

    public static function create(
        string $name,
        ?string $addressLine1,
        string $addressLine2,
        string $country
    ): self {
        return new self(
            $name,
            $addressLine1,
            $addressLine2,
            $country
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    public function getAddressLine2(): string
    {
        return $this->addressLine2;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getFullAddress(): string
    {
        $address = $this->getName();

        if ($this->getAddressLine1()) {
            $address .= "\n" . $this->getAddressLine1();
        }

        if (in_array($this->getCountry(), ['CH', 'FL'])) {
            $address .= "\n" . $this->getAddressLine2();
        } else {
            $address .= sprintf("\n%s-%s", $this->getCountry(), $this->getAddressLine2());
        }

        return $address;
    }

    public function getQrCodeData(): array
    {
        return [
            $this->getAddressLine2() ? self::ADDRESS_TYPE : '',
            $this->getName(),
            $this->getAddressLine1(),
            $this->getAddressLine2(),
            '',
            '',
            $this->getCountry()
        ];
    }
}
