<?php

declare(strict_types=1);

namespace App\Application\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yiisoft\Yii\Console\ExitCode;

#[AsCommand(name: 'hello', description: 'An example command', hidden: false)]
final class HealthCheckCommand extends Command {
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
        $output->writeln('Are ok!');
        return ExitCode::OK;
    }
}
