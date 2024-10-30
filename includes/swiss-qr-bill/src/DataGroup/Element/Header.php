<?php declare(strict_types=1);

namespace Sprain\SwissQrBill\DataGroup\Element;

use Sprain\SwissQrBill\DataGroup\QrCodeableInterface;


final class Header implements QrCodeableInterface
{

    public const QRTYPE_SPC = 'SPC';
    public const VERSION_0200 = '0200';
    public const CODING_LATIN = 1;

    /**
     * Unambiguous indicator for the Swiss QR code.
     */
    private string $qrType;

    /**
     * Version of the specifications (Implementation Guidelines) in use on
     * the date on which the Swiss QR code was created.
     * The first two positions indicate the main version, the following the
     * two positions the sub-version ("0200" for version 2.0).
     */
    private string $version;

    /**
     * Character set code
     */
    private int $coding;

    private function __construct(string $qrType, string $version, int $coding)
    {
        $this->qrType = $qrType;
        $this->version = $version;
        $this->coding = $coding;
    }

    public static function create(string $qrType, string $version, int $coding): self
    {
        return new self($qrType, $version, $coding);
    }

    public function getQrType(): string
    {
        return $this->qrType;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getCoding(): int
    {
        return $this->coding;
    }

    public function getQrCodeData(): array
    {
        return [
            $this->getQrType(),
            $this->getVersion(),
            $this->getCoding()
        ];
    }
}
