<?php

namespace AO\AnalyzerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Command to install IntialData 
 *
 * @author abdessamad oueryemchi
 */
class InitialDataCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('SpendingAnalyzer:initialdata')
                ->setDescription('save intial data in database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $this->getContainer()->get('kernel')->getRootDir() .'/../data/initial_data.sql' ;
        
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        $sql = file_get_contents($file);  // Read file contents
        $em->getConnection()->exec($sql);  // Execute native SQL
        $em->flush();
        
    }

}
