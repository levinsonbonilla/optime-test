<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

class DatabaseConfigurationCommand extends Command
{
    protected static $defaultName = 'db:cf';
    protected static $defaultDescription = 'Configuration of database';
    protected $projectDir;
    protected $em;
    protected $roles;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct();
        $this->projectDir = $kernel->getProjectDir();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('execute', InputArgument::OPTIONAL, 'Use the word start only in first execution why this option clean the database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $execute = trim(strtolower($input->getArgument('execute',null)));

        switch ($execute) {
            case 'start':
                /* Create database */
                shell_exec("php ".str_replace('\\', '/',$this->projectDir)."/bin/console doctrine:database:create --no-interaction");
                shell_exec("php ".str_replace('\\', '/',$this->projectDir)."/bin/console doctrine:database:create --env=test --no-interaction");
                $io->success('Database created successfully');
                break;
            case 'migration':
                /* make migration */
                shell_exec("php ".str_replace('\\', '/',$this->projectDir)."/bin/console make:migration --no-interaction");
                $io->success('Migration created successfully');
                break;            
            default:
                break;
        }

        /* Migration database */
        shell_exec("php ".str_replace('\\', '/',$this->projectDir)."/bin/console doctrine:migrations:migrate --no-interaction");
        shell_exec("php ".str_replace('\\', '/',$this->projectDir)."/bin/console doctrine:migrations:migrate --env=test --no-interaction");
        $io->success('Complete migration');

        // if ($execute == 'start' || $execute == "fix") {
        //     /* load basic data, pending configuration */
        //     shell_exec("php ".str_replace('\\', '/',$this->projectDir)."/bin/console doctrine:fixtures:load --no-interaction");
        //     shell_exec("php ".str_replace('\\', '/',$this->projectDir)."/bin/console doctrine:fixtures:load --env=test --no-interaction");
        //     $io->success('Data inserted successfully');
        // }

        $io->success('Successful operation');
        return Command::SUCCESS;
    }
}
