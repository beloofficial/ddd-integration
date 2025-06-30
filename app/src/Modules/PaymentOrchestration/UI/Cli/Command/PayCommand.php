<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\UI\Cli\Command;

use App\Modules\PaymentOrchestration\Application\UseCase\PayWithCardUseCase;
use App\Modules\PaymentOrchestration\UI\Cli\Presenter\CliPaymentPresenter;
use App\Modules\PaymentOrchestration\UI\Http\Request\PaymentRequest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class PayCommand extends Command
{
    protected static $defaultName = 'app:pay';
    protected static $defaultDescription = 'One-off card payment via chosen gateway';

    public function __construct(
        private readonly PayWithCardUseCase  $useCase,
        private readonly CliPaymentPresenter $presenter,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('gateway', InputArgument::REQUIRED, 'aci|shift4')
            ->addOption('amount',    null, InputOption::VALUE_REQUIRED, 'Amount in minor units (e.g. 1000 for 10.00)')
            ->addOption('currency',  null, InputOption::VALUE_REQUIRED, 'ISO-4217 code')
            ->addOption('card',      null, InputOption::VALUE_REQUIRED, 'PAN')
            ->addOption('exp-month', null, InputOption::VALUE_REQUIRED, '1-12')
            ->addOption('exp-year',  null, InputOption::VALUE_REQUIRED, 'YYYY')
            ->addOption('cvv',       null, InputOption::VALUE_REQUIRED, '3-4 digits');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->presenter->setIo($io);

        $gateway = $input->getArgument('gateway');

        $dto = PaymentRequest::fromArray([
            'amount' => $input->getOption('amount'),
            'currency' => $input->getOption('currency'),
            'card' => $input->getOption('card'),
            'expMonth' => $input->getOption('exp-month'),
            'expYear' => $input->getOption('exp-year'),
            'cvv' => $input->getOption('cvv'),
        ]);

        $this->useCase->execute($gateway, $dto);
        return Command::SUCCESS;
    }
}
