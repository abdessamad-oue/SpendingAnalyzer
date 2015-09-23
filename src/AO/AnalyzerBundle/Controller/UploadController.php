<?php

namespace AO\AnalyzerBundle\Controller;

use AO\AnalyzerBundle\Controller\BaseController;
use AO\AnalyzerBundle\Utils\Help;
use Symfony\Component\Routing\Annotation\Route;
use AO\AnalyzerBundle\Form\Type\AccountType;
use AO\AnalyzerBundle\Entity\Account;
use Symfony\Component\HttpFoundation\Request;
use \AO\AnalyzerBundle\Form\Form\UploadForm;
use \AO\AnalyzerBundle\Entity\Transaction;
use AO\AnalyzerBundle\Utils\LclExcelFile;

/**
 * UploadController
 *
 * @author abde
 */
class UploadController extends BaseController
{

    /**
     * @Route("/upload", name="upload")
     */
    public function indexAction(Request $oRequest)
    {

        $oRepo    = $this->getRepoEntity('Account');
        $aAccount = $oRepo->getAccountsListForm($this->container);

        if (0 == count($aAccount))
        {
            return $this->redirect($this->generateUrl('account'));
        }

        $oForm = $this->createForm(new UploadForm($aAccount));

        $sError = "";
        $bSaved = false;

        if ($oRequest->getMethod() == "POST")
        {
            $oForm->handleRequest($oRequest);
            if ($oForm->isValid())
            {

                $aData = $oForm->getData();

                $nAccountId = $aData['account'];

                $oFile     = $aData['file'];
                $sFileName = $oFile->getClientOriginalName();
                $sExt      = $oFile->guessClientExtension();

                if (!in_array($sExt, array('xls', 'txt')))
                {
                    $sError = ucfirst($this->get('translator')->trans("the file extension is refused"));
                }
                                
                else
                {
                    $sFilesDir = $this->get('kernel')->getRootDir() . $this->container->getParameter('dir_files');

                    $oFile->move($sFilesDir, $sFileName);

                    $sFilePath = $sFilesDir . $sFileName;
                    if (is_file($sFilePath))
                    {
                        
                        $oLclExcel = new LclExcelFile($this->container, $sFilePath);
                        $aExcelContent = $oLclExcel->fileToArray();
                         // $aExcelContent = $oLclExcel->excelToArray();
                         //  Help::pr($aExcelContent);
                        $bSaved        = $this->saveData($nAccountId, $aExcelContent);
                        @unlink($sFilePath);
                    }
                }
            }
        }

        return $this->render('AnalyzerBundle:Upload:index.html.twig', array(
                    'form' => $oForm->createView(),
                    'error' => $sError,
                    'saved' => $bSaved
        ));
    }

    /**
     * Save date in database
     * @param type $nAccountId
     * @param type $aData
     * @return boolean
     */
    private function saveData($nAccountId, $aData)
    {
        try {

            $oRepoAccount = $this->getRepoEntity('Account');
            $oAccount     = $oRepoAccount->findOneById($nAccountId);

            $em = $this->getDoctrine()->getManager();
            foreach ($aData as $key => $data)
            {
                // date
                $sDate       = Help::convertFrenchDate(trim($data['date']));
                $sValeurEuro = trim(str_replace(',', '.', $data['value']));

                $aCateg = LclExcelFile::findCategoryByTransaction($data);

                // check if the some transaction exist in database
                $oRepoTrans   = $this->getRepoEntity('Transaction');
                $oTransaction = $oRepoTrans->findOneBy(array('date' => new \DateTime($sDate), 'wording' => $aCateg['label'], 'amount' => $sValeurEuro));

                if (!empty($aCateg['name']))
                {
                    if (!$oTransaction instanceof Transaction)
                    {
                        $oTransaction = new Transaction();
                    }
                    $oCateg = $this->getCategorieObject($aCateg['name']);
                    // save
                    $oTransaction->setDate(new \DateTime($sDate));
                    $oTransaction->setCategory($oCateg);
                    $oTransaction->setWording($aCateg['label']);
                    $oTransaction->setAmount($sValeurEuro);
                    $oTransaction->setAccount($oAccount);
                    $oTransaction->setModifiedAt(new \DateTime());
                    $em->persist($oTransaction);
                }
            }
            $em->flush();
            return true;
        }
        catch (Exception $ex) {
            $this->get('logger')->error($ex->getMessage());
        }
    }

    /**
     * retouner l entity Category selon le type
     * @param type $sType
     * @return type
     */
    private function getCategorieObject($sType)
    {
        $aParamCateg = $this->container->getParameter('categories');
        $id          = $aParamCateg[$sType];
        $oRep        = $this->getRepoEntity('Category');
        $oCateg      = $oRep->find($id);

        return $oCateg;
    }

}
