<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Process\Process;

class ProjectCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:update';

    protected function configure()
    {
        $this
            ->setDescription('Update the project');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        if (!$this->userConfirmation($input, $output)) {
            return;
        }

        $process = Process::fromShellCommandline('
            composer install &&
            yarn install &&
            yarn encore dev &&
            php bin/console doctrine:schema:drop --force &&
            php bin/console doctrine:schema:update --force &&
            php bin/console doctrine:fixtures:load --no-interaction
        ');

        $process->run(function ($type, $buffer) use ($output) {
            if (Process::ERR === $type) {
                $output->writeln('<comment>'.$buffer.'</comment>');
            } else {
                $output->writeln('<info>'.$buffer.'</info>');
            }
        });

        $output->writeln([
            '.-------------------.',
            '|    UPDATE DONE !  |',
            "'-------------------'"
        ]);
    }

    private function userConfirmation(InputInterface $input, OutputInterface $output): bool
    {
        $question = new ConfirmationQuestion(
            '<question>Make sure that your database is launch, continue ?</question> (y/N)',
            false
        );
        $question->setMaxAttempts(2);
        $helper = $this->getHelper('question');

        if (!$helper->ask($input, $output, $question)) {
            $output->writeln('<info>Command halted. Nothing has been done.</info>');
            return false;
        }

        return true;
    }
}