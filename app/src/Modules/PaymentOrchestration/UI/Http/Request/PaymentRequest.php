<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\UI\Http\Request;

use App\Modules\PaymentOrchestration\Domain\Model\ValueObject\Card;
use App\Modules\PaymentOrchestration\Domain\Model\ValueObject\Money;
use Symfony\Component\Validator\Constraints as Assert;

final class PaymentRequest
{
    #[Assert\NotBlank, Assert\Positive]
    public int $amount;

    #[Assert\NotBlank, Assert\Currency]
    public string $currency;

    #[Assert\NotBlank, Assert\CardScheme(
        schemes: [Assert\CardScheme::VISA, Assert\CardScheme::MASTERCARD]
    )]
    public string $card;

    #[Assert\NotBlank, Assert\Positive, Assert\Length(min: 1, max: 2)]
    #[Assert\Range(notInRangeMessage: 'Month must be between {{ min }} and {{ max }}.', min: 1, max: 12)]
    public int $expMonth;

    #[Assert\NotBlank, Assert\Positive, Assert\Length(min: 4, max: 4)]
    public int $expYear;

    #[Assert\NotBlank, Assert\Length(min: 3, max: 4)]
    public string $cvv;

    public static function fromArray(array $d): self
    {
        $self = new self();
        $self->amount = (int) ($d['amount'] ?? 0);
        $self->currency = (string) ($d['currency'] ?? '');
        $self->card = (string) ($d['card'] ?? '');
        $self->expMonth = (int) ($d['expMonth'] ?? 0);
        $self->expYear = (int) ($d['expYear'] ?? 0);
        $self->cvv = (string) ($d['cvv'] ?? '');
        return $self;
    }
}
