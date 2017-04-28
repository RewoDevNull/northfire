<?php

namespace Northfire\Infrastructure\Symfony\NorthfireBundle\Command;

use Northfire\Domain\Model\Member\Command\RegisterMember;
use Northfire\Domain\Model\Member\VehicleId;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class RegisterMemberCommand
 *
 * @package Northfire\Infrastructure\Symfony\NorthfireBundle\Command
 * @author  Hauke Weber
 */
class RegisterMemberCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('northfire:register-member')
             ->setDescription('Register a new member')
             ->addArgument('firstName', InputArgument::REQUIRED, '')
             ->addArgument('lastName', InputArgument::REQUIRED, '')
             ->addArgument('memberNumber', InputArgument::REQUIRED, '');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandBus = $this->getContainer()->get('prooph_service_bus.northfire_command_bus');
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Registering new member...');

        $command = new RegisterMember();
        $command->firstName = $input->getArgument('firstName');
        $command->lastName = $input->getArgument('lastName');
        $command->memberNumber = $input->getArgument('memberNumber');
        $command->vehicleId = VehicleId::generate()->toString();
        $command->joiningDate = new \DateTime();

        try {
            $commandBus->dispatch($command);
            $io->success('New member successfully registered...');
        } catch (\Exception $ex) {
            $io->error('Error on registering a new member: ' . $ex->getMessage());
        }

        $i = 1;
    }
}