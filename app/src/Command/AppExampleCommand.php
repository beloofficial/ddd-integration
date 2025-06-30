<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:example',
    description: 'Runs the example command for aci or shift4.',
)]
class AppExampleCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('gateway', InputArgument::REQUIRED, 'The gateway name (aci or shift4)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $gateway = $input->getArgument('gateway');

        if (!in_array($gateway, ['aci', 'shift4'])) {
            $output->writeln("<error>Invalid gateway: $gateway. Must be 'aci' or 'shift4'.</error>");
            return Command::FAILURE;
        }

        $output->writeln("Running command for gateway: $gateway");

        // Placeholder for your logic
        return Command::SUCCESS;
    }
}
