<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Plant\Flower;
use App\Model\Plant\Oak;
use App\Model\Plant\Plant;
use App\Model\Plant\Rose;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestOopCommand extends Command
{
    protected static $defaultName = 'app:test:oop';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

       $rose = new Rose();
       $oak = new Oak();

       $this->someFunction($rose);
       $this->someFunction($oak);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
        return Command::SUCCESS;
    }
    public function someFunction(Plant $plant): void
    {
        $plant->grow();
    }
    public function someFunction2(Flower $flower): void
    {
        $flower->photosynthesis();
    }
}
