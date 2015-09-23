<?php

namespace AO\AnalyzerBundle\Controller;

use AO\AnalyzerBundle\Utils\Help;
use AO\AnalyzerBundle\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use AO\AnalyzerBundle\Form\Type\AccountType;
use AO\AnalyzerBundle\Entity\Account;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of AccountController
 *
 * @author Abdessamad OUERYEMCHI
 */
class AccountController extends BaseController
{

    /**
     * account list page
     * 
     * @Route("/account", name="account")
     */
    public function indexAction()
    {
        $oRepo = $this->getRepoEntity('Account');
        $aAcount = $oRepo->findAll();
        
        return $this->render('AnalyzerBundle:Account:index.html.twig', array(
                    'aAccount' => $aAcount
            )
        );
    }

    /**
     * add a account in database
     * 
     * @Route("/account/add", name="addAccount")
     */
    public function addAccountAction(Request $oRequest)
    {
        $oAccount = new Account();
        
        $oForm = $this->createForm(new AccountType($this->container), $oAccount);

        if ($oRequest->getMethod() == "POST")
        {
            $oForm->handleRequest($oRequest);
            if ($oForm->isValid())
            {
                // ajouter le compte dans la base de donnée 
                $em = $this->getDoctrine()->getManager();
                $em->persist($oAccount);
                $em->flush();

                $oRep = $this->getRepoEntity('Account');
                $aAllAcount = $oRep->findAll(); // get all accounts

                $oRequest->getSession()->getFlashBag()->add('flash_account', 'The Account was saved');
                return $this->render('AnalyzerBundle:Account:index.html.twig', array('aAccount' => $aAllAcount));
            }
        }
        return $this->render('AnalyzerBundle:Account:setAccount.html.twig', array(
                    'form' => $oForm->createView(),
                    'type' => 'add'
                        )
        );
    }

    /**
     * Modify a account information
     * 
     * @Route("/account/set/{id}", name="setAccount", defaults={"id"=0})
     */
    public function setAccountAction($id, Request $oRequest)
    {
        
        $oAccount = $this->getRepoEntity('Account')->find($id);
        if (null == $oAccount)
        {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Account with id ' . $id . ' not found');
        }

        $oForm = $this->createForm(new AccountType($this->container), $oAccount);

        if ($oRequest->getMethod() == "POST")
        {
            if ($oForm->handleRequest($oRequest)->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                
                $sMessage = ucfirst($this->get('translator')->trans('the account was modified'));
                $oRequest->getSession()->getFlashBag()->add('flash_account', $sMessage);
                return $this->render('AnalyzerBundle:Account:index.html.twig', array('aAccount' => $this->getRepoEntity('Account')->findAll()));
            }
        }

        return $this->render('AnalyzerBundle:Account:setAccount.html.twig', array(
                    'form' => $oForm->createView(),
                    'type' => 'change' , 
                    'id' => $id
                        )
        );
    }

}
