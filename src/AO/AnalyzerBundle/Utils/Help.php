<?php

namespace AO\AnalyzerBundle\Utils;
use Doctrine\Common\Util\Debug;

/**
 * Help - Tools Class 
 *
 * @author Abdessamad OUERYEMCHI
 */
class Help
{
    
    /**
     * execute print_r with <pre> tag
     * @param type $param
     * @param type $die
     * @return type
     */
    public static function pr($param, $die = true)
    {
        echo '<pre>';
        print_r($param);
        echo '</pre>';

        if ($die)
        {
            die;
        }

        return;
    }

    /**
     * execute un var_dump with <pre> tag
     * @param type $param
     * @param type $die
     * @return type
     */
    public static function dump($param, $die = true)
    {
        echo '<pre>';
        Debug::dump($param);
        echo '</pre>';

        if ($die)
        {
            die;
        }

        return;
    }

    /**
     * convert french date  to date 
     * @param string $sDateFr
     * @return string 
     */
    public static function convertFrenchDate($sDateFr)
    {
        if (empty($sDateFr))
        {
            return null;
        }

        $aTmp = explode('/', $sDateFr);
        return $aTmp[2] . '-' . $aTmp[1] . '-' . $aTmp[0];
    }   
    
    /**
     * get Date from a english date string
     * @param string 
     */
    public static function getCustomDate($sFormat= 'Y-m-d', $strToTime='this month')
    {
        return date("$sFormat", strtotime("$strToTime"));
    }
    
    /**
     * number of pages according to data 
     * @param type $nTotalRecord
     * @param type $nPerPage
     */
    public static function getNumberPages($nTotalRecord, $nPerPage = 20)
    {
        $nPages = (int) ($nTotalRecord / $nPerPage);
        $nModulo = $nTotalRecord / $nPerPage;
        
        if($nModulo>0)
        {
            $nPages++;
        }
        
        return $nPages;
        
    }
    
}
