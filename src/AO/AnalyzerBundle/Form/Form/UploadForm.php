<?php

namespace AO\AnalyzerBundle\Form\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
  /**
 * Description of UploadForm
 *
 * @author abde
 */
class UploadForm extends AbstractType
{

    private $aAccount;

    public function __construct($aAccount)
    {
        $this->aAccount = $aAccount;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('account', 'choice', array('required' => true, 'choices' => $this->aAccount, 'multiple' => false));

        $builder->add('file_type', 'choice', array(
            'required' => true,
            'choices' => array(
                'lcl' => '1. LCL Bank transactions data file (tab separated values)'
            ),
            'constraints' => array(
                new Assert\NotBlank()
            ),
            'multiple' => false,
            'expanded' => false
        ));

        $builder->add('file', 'file', array(
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank()
        )));
    }

    public function getName()
    {
        return '';
    }

}
