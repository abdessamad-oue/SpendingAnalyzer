<?php

namespace AO\AnalyzerBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use AO\AnalyzerBundle\Utils\Help;
use \Doctrine\Common\Util\Debug;

/**
 * Service pour effectuer differents statis sur les transactions ...
 *
 * @author Abdessamad OUERYEMCHI
 * 
 */
class Statistics
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * to do later !
     * @param type $aTransactions
     * @return type
     */
    public function globalSum($aTransactions)
    {
        $nTotalDebitPeriod  = 0;
        $nTotalCreditPeriod = 0;

           
        return array('debit' => $nTotalDebitPeriod , 'credit' => $nTotalCreditPeriod);
    }

}
