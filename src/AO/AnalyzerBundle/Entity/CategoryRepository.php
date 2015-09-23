<?php

namespace AO\AnalyzerBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 */
class CategoryRepository extends EntityRepository
{

    /**
     * get a list of categories
     * @return array
     */
    public function getCategories($container)
    {
        $aResponse = array();

        try {
            $oQB       = $this->createQueryBuilder('c');
            $aoCategories = $oQB->getQuery()->getResult();
            foreach ($aoCategories as $oCateg)
            {
                $aResponse[$oCateg->getId()] = $container->get('translator')->trans($oCateg->getLabel());
            }
        }
        catch (Doctrine\ORM\NoResultException $Exception) {

            $container->get('logger')->error($Exception->getMessage());
        }
        return $aResponse;
    }

}
