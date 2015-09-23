<?php

namespace AO\AnalyzerBundle\Form\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Form pour les stats basiques
 *
 * @author Abdessamad OUERYEMCHI 
 */
class BasicForm extends AbstractType
{

    private $aAccount;
    private $sDefaultDateBegin;
    private $sDefaultDateEnd;
    private $aCategories;
    private $bSearchByWording;
    
    
    public function __construct($aAccount,$sDateBegin=null, $sDateEnd=null, $aCategories= array(), $bSearchByWording =false)
    {
        $this->aAccount = $aAccount;
        $this->sDefaultDateBegin = $sDateBegin;
        $this->sDefaultDateEnd = $sDateEnd;
        $this->aCategories = $aCategories;
        $this->bSearchByWording = $bSearchByWording;
        
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('account', 'choice', array('required' => true, 'choices' => $this->aAccount, 'multiple' => false));

        $builder->add('date_begin', 'text', array(
            'required' => true,
            'trim' => true,
            'max_length' => 10,
            'data' => $this->sDefaultDateBegin,
            'constraints' => array( 
                new Assert\Date(),
                new Assert\NotBlank()
                )
        ));

        $builder->add('date_end', 'text', array(
            'required' => true,
            'max_length' => 10,
            'trim' => true,
            'data' => $this->sDefaultDateEnd,
            'constraints' => array( 
                new Assert\Date(),
                new Assert\NotBlank()
                )
        ));
        
        if(!empty($this->aCategories))
        {
            $aChoices = array_merge(array(0 => '-- CATEGORIE --'), $this->aCategories);            
            $builder->add('category', 'choice', array(
                'required' => true,
                'choices' =>$aChoices
            ));
        }
        if($this->bSearchByWording)
        {
             $builder->add('wording', 'text', array(
            'required' => false,
            'trim' => true,
            'max_length' => 50,
            'data' => '',
        ));
        }
        
    }

    public function getName()
    {
        return '';
    }

}
