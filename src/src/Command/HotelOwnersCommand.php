<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:hotel-owners',
    description: 'Add a short description for your command',
)]
class HotelOwnersCommand extends Command
{


    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, $name = null)
    {
        parent::__construct($name);
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->findAll();
        $rows = [];

        foreach ($users as $user) {
            if ($user->hasRole("ROLE_HOTEL_OWNER")) {
                $rows[] = [$user->getEmail(), implode(", ", $user->getHotels()->toArray())];
            }
        }

        $io = new SymfonyStyle($input, $output);
        $io->table(["User", "Hotels"], $rows);

        return Command::SUCCESS;
    }
}
