<?php
/**
 * Created by PhpStorm.
 * User: samuelerb
 * Date: 18.12.17
 * Time: 12:52
 */

namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class ResetDatabase extends Command
{
    protected function configure() {
        $this
            ->setName('App:reset-database')
            ->setDescription('Resets/initializes the database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $input->setInteractive(false);

        $app = $this->getApplication();

        try {
            $app->find('doctrine:database:drop')
                ->run(new StringInput('--force'), $output);

            $app->find('doctrine:database:create')
                ->run($input, $output);

            $app->find('doctrine:schema:create')
                ->run($input, $output);

            $app->find('doctrine:fixtures:load')
                ->run($input, $output);
        } catch (\Exception $e) {
            $output->writeln("Oops, something went wrong: " . $e->getMessage());
            $output->writeln("Stacktrace:\n" . $e->getTraceAsString());
        }
    }

}