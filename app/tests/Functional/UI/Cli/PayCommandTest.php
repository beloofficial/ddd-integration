<?php

declare(strict_types=1);

namespace Tests\Functional\UI\Cli;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class PayCommandTest extends KernelTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }
    public function testCliPaymentAci(): void
    {
        $application = new Application(self::$kernel);

        $command = $application->find('app:pay');
        $tester  = new CommandTester($command);

        $exitCode = $tester->execute([
            'gateway'      => 'aci',
            '--amount'     => '1000',
            '--currency'   => 'EUR',
            '--card'       => '4200000000000000',
            '--exp-month'  => '12',
            '--exp-year'   => '2038',
            '--cvv'        => '123',
        ]);

        $tester->assertCommandIsSuccessful();
        $output = $tester->getDisplay();
        $this->assertStringContainsString('Payment authorised', $output);
        $this->assertSame(0, $exitCode);
    }

    public function testCliPaymentShift4(): void
    {
        $application = new Application(self::$kernel);

        $command = $application->find('app:pay');
        $tester  = new CommandTester($command);

        $exitCode = $tester->execute([
            'gateway'      => 'shift4',
            '--amount'     => '1000',
            '--currency'   => 'EUR',
            '--card'       => '4200000000000000',
            '--exp-month'  => '12',
            '--exp-year'   => '2038',
            '--cvv'        => '123',
        ]);

        $tester->assertCommandIsSuccessful();
        $output = $tester->getDisplay();
        $this->assertStringContainsString('Payment authorised', $output);
        $this->assertSame(0, $exitCode);
    }
}
