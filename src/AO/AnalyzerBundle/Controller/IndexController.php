<?php

namespace AO\AnalyzerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AO\AnalyzerBundle\Entity\Category;
use AO\AnalyzerBundle\Utils\Help;
use AO\AnalyzerBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use AO\AnalyzerBundle\Form\Form\BasicForm;
use Doctrine\Common\Util\Debug;

/**
 * IndexController 
 * 
 * @author Abdessamad OUERYEMCHI
 */
class IndexController extends BaseController
{

    /**
     * Index
     * @Route("/home", name="home")
     */
    public function indexAction(Request $oRequest)
    {

        $oRepo    = $this->getRepoEntity('Account');
        $aAccount = $oRepo->getAccountsListForm($this->container);
        $nAccount = count($aAccount);

        // si aucun compte dans la base de donnnée
        if (0 == $nAccount)
        {
            return $this->redirect($this->generateUrl('account'));
        }

        $sDateBeginLastMonth = Help::getCustomDate('Y-m-d', 'first day of last month');
        $sDateEndLastMonth   = Help::getCustomDate('Y-m-d', 'last day of last month');

        $oForm = $this->createForm(new BasicForm($aAccount, $sDateBeginLastMonth, $sDateEndLastMonth));

        $oRep = $this->getRepoEntity('Transaction');
        $oObj = $oRep->getLastTransaction();

        $oLastDate = (!is_null($oObj) ) ? $oObj->getCreatedAt() : null;

        return $this->render('AnalyzerBundle:Index:index.html.twig', array(
                    'oLastDate' => $oLastDate,
                    'form' => $oForm->createView(),
                    'formAction' =>$this->generateUrl('basicStatistics'),
                    'nAccount' => $nAccount
        ));
    }

    /**
     * Index
     * @Route("/home/basic_statistics", name="basicStatistics")
     */
    public function getGeneralStatsAction(Request $oRequest)
    {

        $aCategories = $this->container->getParameter('categories');

        $aRequest     = $oRequest->request->all();
        $aSumDebit    = $aSumCredit   = array();
        $sAccountType = null;
        if (!empty($aRequest['date_begin']) && !empty($aRequest['date_end']))
        {
            $oAccount   = $this->getRepoEntity('Account')->find($aRequest['account']);
            $oDateBegin = new \DateTime($aRequest['date_begin']);
            $oDateEnd   = new \DateTime($aRequest['date_end']);

            $sAccountType = $oAccount->getAccountType()->getCode();
            $oRep         = $this->getRepoEntity('Transaction');

            $aSumDebit  = $this->formatSumArray($oRep->SumByCategory($oAccount, $oDateBegin, $oDateEnd, 'DEBIT'));
            $aSumCredit = $this->formatSumArray($oRep->SumByCategory($oAccount, $oDateBegin, $oDateEnd, 'CREDIT'));
        }
        
      //  Help::pr($aSumCredit);
        
        return $this->render('AnalyzerBundle:Index:spendingByCategory.html.twig', array(
                    'aReq' => $aRequest,
                    'aCategLabel' => $this->container->getParameter('categorieLabel'),
                    'sAccountType' => $sAccountType,
                    'aCategLabels' => array_keys($aCategories),
                    'aSumDebit' => $aSumDebit,
                    'aSumCredit' => $aSumCredit,
                    'nTotalCredit' => array_sum($aSumCredit),
                    'nTotalDebit' => array_sum($aSumDebit)
        ));
    }

    /**
     * formater le tableau des sommes pour le Twig (index)
     * @param type $aSumByCategory
     * @return type
     */
    private function formatSumArray($aSumByCategories)
    {
        $aCategories = $this->container->getParameter('categories');
        $aCategLabel = array_keys($aCategories);

        $aResult = array_fill_keys($aCategLabel, null);

        if (!empty($aSumByCategories))
        {
            foreach ($aSumByCategories as $aSumCateg)
            {
                $aResult[$aSumCateg['code']] = round($aSumCateg['nSum'], 2);
            }
        }
        return $aResult;
    }

    /**
     * transaction list by category and date
     * @Route("/transaction/list/{account}/{date_begin}/{date_end}/{category}/{type}", name="transactionList", defaults={"account"=null, "date_begin"=null, "date_end"=null, "category"=null,"type"="credit"})
     */
    public function transactionListAction($account, $date_begin, $date_end, $category, $type)
    {
        $oAccount  = $this->getRepoEntity('Account')->find($account);
        $oRep      = $this->getRepoEntity('Transaction');
        $oCategory = $this->getRepoEntity('Category')->findOneByCode($category);

        $aTrans = $oRep->findByAccount($oAccount, $date_begin, $date_end, $oCategory, null, strtoupper($type));


        return $this->render('AnalyzerBundle:Index:transactionList.html.twig', array(
                    'aTransactions' => $aTrans,
                    'sCateg' => $oCategory->getLabel()
        ));
    }

}
