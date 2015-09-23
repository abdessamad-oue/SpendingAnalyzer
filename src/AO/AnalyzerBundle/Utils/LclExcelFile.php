<?php

namespace AO\AnalyzerBundle\Utils;

use Symfony\Component\DependencyInjection\Container;

/**
 * LclExcelFile - Class for extractinfg Transaction data from Microsoft Excel File (LcL bank model)
 *
 * @author abdessamad O.
 */
class LclExcelFile
{

    private $oContainer;
    private $sFile;

    public function __construct(Container $container, $sFile = null)
    {
        $this->oContainer = $container;
        $this->sFile      = $sFile;
    }

    /**
     * convert a transaction file content to array - (function for a tabulation separated file )
     * @return array
     * 
     */
    public function fileToArray()
    {
        try {
            $aDataFile = array();
            $fp        = fopen($this->sFile, 'r');

            while (!feof($fp))
            {
                $line        = fgets($fp, 2048);
                $delimiter   = "\t";
                $aDataFile[] = str_getcsv($line, $delimiter);
            }
            fclose($fp);

            $aResult = array();


            foreach ($aDataFile as $key => $aData)
            {
                if (count($aData) > 5)
                {
                    foreach ($aData as $k => $value)
                    {
                        switch ($k):
                            case 0:
                                $k = 'date';
                                break;
                            case 1:
                                $k = 'value';
                                break;
                            case 2:
                                $k = 'type';
                                break;
                            case 3:
                                $k = 'extra';
                                break;
                            case 4:
                                $k = 'word_1';
                                break;
                            case 5:
                                $k = 'word_2';
                                break;
                        endswitch;

                        $aResult[$key][$k] = trim($value);
                    }
                }
            }
        }
        catch (\Exception $ex) {
            $this->oContainer->get('logger')->error($ex->getMessage());
        }
        return $aResult;
    }

    /**
     * convert a transaction file content to array - (function for a excel file .xls )
     *
     * @return array
     */
    public function excelToArray()
    {

        try {
            $oPhpExcel = $this->oContainer->get('phpexcel')->createPHPExcelObject($this->sFile);
            $nStart    = 1;
            $nEnd      = $oPhpExcel->getActiveSheet()->getHighestRow();
            $aResult   = array();



            for ($i = $nStart; $i <= $nEnd; $i++)
            {
                $row = $oPhpExcel->getActiveSheet()->getRowIterator($i)->current();

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                foreach ($cellIterator as $k => $cell)
                {
                    switch ($k):
                        case 0:
                            $k = 'date';
                            break;
                        case 1:
                            $k = 'value';
                            break;
                        case 2:
                            $k = 'type';
                            break;
                        case 3:
                            $k = 'extra';
                            break;
                        case 4:
                            $k = 'word_1';
                            break;
                        case 5:
                            $k = 'word_2';
                            break;
                    endswitch;

                    $aResult[$i][$k] = trim($cell->getValue());
                }
            }
        }
        catch (\Exception $ex) {
            $this->oContainer->get('logger')->error($ex->getMessage());
        }
        return $aResult;
    }

    /**
     * lire le contenue d'une cellule
     * @param type $nRow
     * @param type $sColumn
     */
    public function getCellValue($oPhpExcel, $sColumn, $sRow)
    {
        return $oPhpExcel->getActiveSheet()->getCell($sColumn . $sRow)->getValue();
    }

    /**
     * trouver la category de la transaction dans le contenu du Excel
     * @param array $aData
     * @return array
     */
    public static function findCategoryByTransaction($aData)
    {
        $sCateg = null;
        $sLabel = "";

        if (strstr($aData['word_1'], 'CB') !== false && strstr($aData['word_1'], 'RETRAIT') !== false)
        {
            $sCateg = 'CASH';
            $sLabel = $aData['word_1'];
        }
        elseif (strstr($aData['word_1'], 'CB') !== false)
        {
            $sCateg = 'CC';
            $sLabel = $aData['word_1'];
        }
        elseif (strstr($aData['word_1'], 'PRLV') !== false)
        {
            $sCateg = 'LEVY';
            $sLabel = $aData['word_1'];
        }
        elseif (strstr($aData['word_1'], 'VIREMENT') !== false || strstr($aData['word_1'], 'VIR.') !== false || strstr($aData['word_2'], 'VIREMENT') !== false)
        {
            $sCateg = 'TRANSFER';
            $sLabel = !empty($aData['word_1']) ? $aData['word_1'] : $aData['word_2'];
        }
        elseif (strstr($aData['word_2'], 'LCL A LA CARTE') !== false || strstr($aData['word_2'], 'COTISATION') !== false || strstr($aData['word_2'], 'INTERETS') !== false)
        {
            $sCateg = 'BANK_CHARGE';
            $sLabel = !empty($aData['word_1']) ? $aData['word_1'] : $aData['word_2'];
        }
        elseif (in_array($aData['type'], array('Ch?que', 'Chèque')) && !empty($aData['extra']))
        {
            $sCateg = 'CHE';
            $sLabel = $aData['extra'];
        }
        elseif (strstr($aData['word_2'], 'REM CHQ') !== false)
        {
            $sCateg = 'RE_CHE';
            $sLabel = $aData['word_2'];
        }

        return array('name' => $sCateg, 'label' => $sLabel);
    }

}
