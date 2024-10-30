<?php declare(strict_types=1);

namespace Sprain\SwissQrBill\DataGroup\Element;

use Sprain\SwissQrBill\DataGroup\QrCodeableInterface;


final class AlternativeScheme implements QrCodeableInterface
{

    /**
     * Parameter character chain of the alternative scheme
     */
    private string $parameter;

    private function __construct(string $parameter)
    {
        $this->parameter = $parameter;
    }

    public static function create(string $parameter): self
    {
        return new self($parameter);
    }

    public function getParameter(): string
    {
        return $this->parameter;
    }

    public function getQrCodeData(): array
    {
        return [
            $this->getParameter()
        ];
    }
}
