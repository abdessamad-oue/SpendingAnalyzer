<?php

namespace AO\AnalyzerBundle\Entity;

use Doctrine\ORM\EntityRepository;
use \Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Description of TransactionRepository
 *
 * @author Abdessamad O.
 */
class TransactionRepository extends EntityRepository
{

    /**
     * CREDIT OPERATION
     */
    const CREDIT = 'CREDIT';

    /**
     * DEBIT operation
     */
    const DEBIT = 'DEBIT';

    /**
     * Number of result per page (if pagination)
     */
    const PER_PAGE = 20;

    /**
     * get last Transaction (by date of creation) in database 
     * @return Transaction 
     */
    public function getLastTransaction()
    {
        // $queryBuilder = $this->_em->createQueryBuilder()->select('a')->from($this->_entityName, 'a');
        try {
            $oQB = $this->createQueryBuilder('t');
            $oQB->addOrderBy('t.created_at', 'DESC');
            $oQB->setMaxResults(1);
            return $oQB->getQuery()->getSingleResult();
        }
        catch (NoResultException $ex) {
            return null;
        }
    }

    /**
     * recuperer les transactions d un compte et entres 2 dates
     * @param type $oAccount
     * @param type $sDateBegin
     * @param type $sDateEnd
     * @return null
     */
    public function findByAccount($oAccount, $sDateBegin, $sDateEnd, $oCategory = null, $sWording=null , $sOperation = null, $nFirstResult = null, $nPerPage = null)
    {
        try {

            if (is_null($nPerPage))
            {
                $nPerPage = self::PER_PAGE;
            }

            $sOperator = null;
            if (!is_null($sOperation))
            {
                $sOperator = '<';
                if ($sOperation == self::CREDIT)
                {
                    $sOperator = '>';
                }
            }

            $oQB = $this->createQueryBuilder('t');
            $oQB->innerJoin('t.account', 'a');
            $oQB->innerJoin('t.category', 'c');

            $oQB->OrderBy('t.date', 'ASC');
            $oQB->where('t.account = :account')->setParameter('account', $oAccount);
            $oQB->andWhere('t.date >= :date_b ')->setParameter('date_b', $sDateBegin);
            $oQB->andWhere('t.date <= :date_f ')->setParameter('date_f', $sDateEnd);
            if (!is_null($sOperator))
            {
                $oQB->andWhere('t.amount ' . $sOperator . ' :val')->setParameter('val', 0);
            }
            if (!is_null($oCategory))
            {
                $oQB->andWhere('t.category = :category')->setParameter('category', $oCategory);
            }
            if(!is_null($sWording))
            {
                $oQB->andWhere('t.wording LIKE :word')->setParameter('word', '%' . $sWording. '%');
            }

            $oQB->addSelect('c');
            $oQB->addSelect('a');

            $nTotalResult = count($oQB->getQuery()->getResult());
            if (!is_null($nFirstResult))
            {
                $oQB->setFirstResult($nFirstResult);
                $oQB->setMaxResults($nPerPage);

                $paginator           = new Paginator($oQB->getQuery(), $fetchJoinCollection = true);

                return array(
                    'TotalRecordCount' => $nTotalResult,
                    'Records' => $paginator->getQuery()->getResult()
                );
            }

            return $oQB->getQuery()->getResult();
        }
        catch (NoResultException $ex) {

            return null;
        }
    }

    /**
     * retourner la somme des transctions par category , pour un compt bancaire donné
     * @param type $oAccount
     * @param type $sDateBegin
     * @param type $sDateEnd
     */
    public function SumByCategory($oAccount, $sDateBegin, $sDateEnd, $sOperation)
    {
        try {

            $sOperator = '<';
            if ($sOperation == self::CREDIT)
            {
                $sOperator = '>';
            }

            $oQB = $this->createQueryBuilder('t');
            $oQB->innerJoin('t.account', 'a');
            $oQB->innerJoin('t.category', 'c');

            $oQB->select('SUM(t.amount) as nSum');
            $oQB->addSelect('c.code');

            $oQB->where('t.account = :account')->setParameter('account', $oAccount);
            $oQB->andWhere('t.date >= :date_b ')->setParameter('date_b', $sDateBegin);
            $oQB->andWhere('t.date <= :date_f ')->setParameter('date_f', $sDateEnd);
            $oQB->andWhere('t.amount ' . $sOperator . ' :val')->setParameter('val', 0);

            $oQB->groupBy('c.code');
            $oQB->orderBy('c.id');

            //  \AO\AnalyzerBundle\Utils\Help::pr($oQB->getQuery()->getSQL());

            return $oQB->getQuery()->getArrayResult();
        }
        catch (NoResultException $ex) {
            
        }
    }

}
