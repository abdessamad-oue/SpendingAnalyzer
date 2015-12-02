<?php

namespace AO\AnalyzerBundle\Controller;

use AO\AnalyzerBundle\Utils\Help;
use AO\AnalyzerBundle\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use AO\AnalyzerBundle\Form\Type\AccountType;
use AO\AnalyzerBundle\Entity\Account;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Util\Debug;
use AO\AnalyzerBundle\Form\Form\BasicForm;
use \AO\AnalyzerBundle\Entity\Transaction;
use AO\AnalyzerBundle\Form\Type\TransactionType;
use Symfony\Component\HttpFoundation\Response;

/**
 * TransactionController
 *
 * @author Abdessamad OUERYEMCHI
 */
class TransactionController extends BaseController
{

    /**
     * @Route("/transaction", name="transaction")
     */
    public function indexAction()
    {

        $oRepo    = $this->getRepoEntity('Account');
        $aAccount = $oRepo->getAccountsListForm($this->container);
        $nAccount = count($aAccount);
        $aLastSearch = $this->findInSession('lastSearch');
        // si aucun compte dans la base de donnnée
        if (0 == $nAccount)
        {
            return $this->redirect($this->generateUrl('account'));
        }

        $sDateBegin = Help::getCustomDate('Y-m-d', 'first day of last month');
        $sDateEnd   = Help::getCustomDate('Y-m-d', 'last day of last month');
        if (!empty($aLastSearch))
        {
            $sDateBegin = $aLastSearch['date_begin'];
            $sDateEnd   = $aLastSearch['date_end'];
        }


        $aCategories = $this->getRepoEntity('Category')->getCategories($this->container);

        $oForm = $this->createForm(new BasicForm($aAccount, $sDateBegin, $sDateEnd, $aCategories, true));

        return $this->render('AnalyzerBundle:Transaction:index.html.twig', array(
                    'form' => $oForm->createView(),
                    'formAction' => $this->generateUrl('findTransactions'),
        ));
    }

    /**
     * @Route("/transaction/create", name="addTransaction")
     */
    public function addAction(Request $oRequest)
    {

        $oTrans = new \AO\AnalyzerBundle\Entity\Transaction();
        $oForm  = $this->createForm(new TransactionType($this->container), $oTrans);

        if ($oRequest->getMethod() == "POST")
        {
            $oForm->handleRequest($oRequest);
            if ($oForm->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($oTrans);
                $em->flush();

                return new Response(1);
            }
        }
        return $this->render('AnalyzerBundle:Transaction:setTransaction.html.twig', array(
                    'form' => $oForm->createView()
        ));
    }

    /**
     * @Route("/transaction/set/{id}", name="setTransaction", defaults={"id"=0})
     */
    public function setAction($id, Request $oRequest)
    {

        $oTrans = $this->getRepoEntity('Transaction')->find($id);
        if (is_null($oTrans))
        {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Transaction with id ' . $id . ' not found');
        }

        $oForm = $this->createForm(new TransactionType($this->container), $oTrans);

        if ($oRequest->getMethod() == 'POST')
        {
            if ($oForm->handleRequest($oRequest)->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return new Response(1);
            }
        }

        return $this->render('AnalyzerBundle:Transaction:setTransaction.html.twig', array(
                    'form' => $oForm->createView(),
                    'id' => $oTrans->getId()
        ));
    }

    /**
     * @Route("/transaction/find", name="findTransactions")
     */
    public function findTransactionsAction(Request $oRequest)
    {
        // $aCategories = $this->container->getParameter('categories');
        $nPages       = $nFirstResult = 0;
        $aRequest     = $oRequest->request->all();
        $nPage        = isset($aRequest['page']) ? $aRequest['page'] : 1;
        $nPerPage     = $this->container->getParameter('per_page');
        $sWording     = !empty($aRequest['wording']) ? $aRequest['wording'] : null;

        if ($nPage > 1)
        {
            $nFirstResult = ($nPerPage * ($nPage - 1));
        }

        if (!empty($aRequest['date_begin']) && !empty($aRequest['date_end']))
        {
            $oAccount   = $this->getRepoEntity('Account')->find($aRequest['account']);
            $sDateBegin = $aRequest['date_begin'];
            $sDateEnd   = $aRequest['date_end'];
            $nCategId   = $aRequest['category'];

            $oRepTransaction = $this->getRepoEntity('Transaction');
            $oCategory       = null;

            if ($nCategId != 0)
            {
                $oCategory = $this->getRepoEntity('Category')->findOneById($nCategId);
            }
            $aoTransactions = $oRepTransaction->findByAccount($oAccount, $sDateBegin, $sDateEnd, $oCategory, $sWording, null, $nFirstResult, $nPerPage);
            $nPages         = Help::getNumberPages($aoTransactions['TotalRecordCount'], $nPerPage);
        }


        return $this->render('AnalyzerBundle:Transaction:list.html.twig', array(
                    'aoTransactions' => $aoTransactions,
                    'nPages' => $nPages,
                    'currentPage' => $nPage
        ));
    }

    /**
     * @Route("/transaction/delete", name="deleteTransactions")
     */
    public function deleteAction(Request $oRequest)
    {
        $id = $oRequest->request->get('id');

        $oTrans = $this->getRepoEntity('Transaction')->find($id);
        if (is_null($oTrans))
        {
            return new Response(0);
        }

        $oEm = $this->getDoctrine()->getEntityManager();
        $oEm->remove($oTrans);
        $oEm->flush();

        return new Response(1);
    }

}
