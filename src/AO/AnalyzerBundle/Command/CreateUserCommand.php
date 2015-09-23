<?php

namespace AO\AnalyzerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AO\AnalyzerBundle\Entity\User;

/**
 * Command to create user for the app
 *
 * @author abdessamad oueryemchi
 */
class CreateUserCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('SpendingAnalyzer:createuser')
                ->setDescription('create SpendingAnalyzer user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');

        
         $username = $dialog->ask(
                $output, 'Please enter the username : ', false
        );

        if (!preg_match("#^[a-z\d_]{4,10}$#", $username))
        {
            $output->writeln("<error>[ERROR]\t The username : " . $username . " is refused</error>");
            $output->writeln("<error>Accepted username format : 4-10 lowercase character ('_' is accepted) </error>");
            die();
        }
        
        $password = $dialog->askHiddenResponse(
                $output, 'Please enter the password : ', false
        );

        if (!preg_match("#^[A-Za-z\d$@$!%*?&]{6,20}$#", $password))
        {
            $output->writeln("<error>[ERROR]\t The password is refused</error>");
            $output->writeln("<error>Accepted username format : 6-20 characters or numbers ('$@$!%*?&' are accepted) </error>");
            die();
        }

        // save in database
        $oEm   = $this->getContainer()->get('doctrine')->getManager();
        $oUser = new User();
        $oUser->setUsername($username);
        $oUser->setPassword(sha1($password));
        $oUser->setSalt("");

        $oEm->persist($oUser);
        $oEm->flush();

        $output->writeln("<info>[SUCCESS]\t The User : " . $username . " is created</info>");
    }

}
