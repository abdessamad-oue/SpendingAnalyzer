<?php

namespace AO\AnalyzerBundle\Controller; 

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Base Controller for the AnalyzerBundle
 *
 * @author Abdessamad O.
 */
class BaseController extends Controller
{
   
    /**
     * get a Repository for a Doctrine Entity 
     * @param string  $sEntity Description
     * @author Abdessamad O.
     */
    public function getRepoEntity($sEntity)
    {
         return $this->getDoctrine()->getManager()->getRepository('AnalyzerBundle:'.$sEntity);
         
    }
    
    /**
     * verify if we have accounts in database
     * @return void
     */
    public function accountInDatabase()
    {
        $aAccount = $this->getRepoEntity('Account')->getAccountsListForm($this->container);
        $nAccount = count($aAccount);

        if (0 == $nAccount)
        {
            return $this->redirect($this->generateUrl('account'));
        }
        return $aAccount;
    }
    
}
