<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\UI\Http\Controller;

use App\Modules\PaymentOrchestration\Application\UseCase\PayWithCardUseCase;
use App\Modules\PaymentOrchestration\UI\Http\Presenter\JsonPaymentPresenter;
use App\Modules\PaymentOrchestration\UI\Http\Request\PaymentRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PayController extends AbstractController
{
    #[Route('/app/example/{gateway}', methods: ['POST'])]
    public function __invoke(
        string $gateway,
        PaymentRequest $request,
        PayWithCardUseCase $useCase,
        JsonPaymentPresenter $presenter,
    ): JsonResponse {
        $useCase->execute($gateway, $request);

        return $presenter->view();
    }
}
