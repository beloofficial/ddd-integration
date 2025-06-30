<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\UI\Http\Resolver;

use App\Modules\PaymentOrchestration\UI\Http\Request\PaymentRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class PaymentRequestResolver implements ArgumentValueResolverInterface
{
    public function __construct(private readonly ValidatorInterface $validator) {}

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === PaymentRequest::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $dto = PaymentRequest::fromArray($request->request->all());
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            throw new ValidationFailedException($dto, $violations);
        }

        yield $dto;
    }
}
