<?php

namespace AO\AnalyzerBundle\Controller; 

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AO\AnalyzerBundle\Utils\Help;
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
     * find a variable in php session
     * @param string $sName
     * @return mixed
     */
    public function findInSession($sName)
    {
        $oSession = $this->getRequest()->getSession();
                
        if($oSession->get($sName))
        {
            return $oSession->get($sName);
        }
        return null;
    }
}
