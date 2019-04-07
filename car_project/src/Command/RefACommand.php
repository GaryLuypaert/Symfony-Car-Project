<?php

namespace App\Command;

use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RefACommand extends Command
{
    protected static $defaultName = 'app:ref:a';

    private $carRepository;
    private $manager;

    public function __construct(CarRepository $carRepository, EntityManagerInterface $em)
    {
        $this->manager = $manager;
        $this->carRepository = $carRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Affiche les annonces commençant par la lettre A')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cars = $this->carRepository->findBy(['user' => null]);

        $total = count($cars);

        $output->writeln("<info>Il y a $total annonces commençant par A</info>");

    }
}
