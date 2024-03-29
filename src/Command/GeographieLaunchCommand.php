<?php

namespace App\Command;

use App\Repository\DepartementRepository;
use App\Services\ApiManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:geographie:launch',
    description: 'Add a short description for your command',
)]
class GeographieLaunchCommand extends Command
{
    public function __construct(private readonly ApiManager $apiManager, private readonly DepartementRepository $departementRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import regions and departments data')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $regions = $this->apiManager->getRegions();
        $departments = $this->apiManager->getDepartements();
        $code = [];
        foreach ($departments as $department) {
            $code[] = $department->getCode();
        }
        foreach ($code as $codeDepartment) {
            $cities = $this->apiManager->getCities($codeDepartment);
        }


        $output->writeln('Regions, departments and cities data imported successfully.');

        return Command::SUCCESS;
    }
}
