<?php

namespace AO\AnalyzerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use \Symfony\Component\DependencyInjection\Container;

/**
 * Description of AccountType
 *
 * class form pour ajout/modification d'un enregistrement de la table account 
 *  
 * @author Abdessamad O.
 */
class AccountType extends AbstractType
{

    private $oContainer;

    public function __construct(Container $oContainer)
    {
        $this->oContainer = $oContainer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add("code", "text", array(
                    'required' => true,
                    'label' => ucfirst($this->oContainer->get('translator')->trans('code')),
                    'attr' => array('class' => 'form-control'),
                ))
                ->add("comment", "text", array(
                    'label' => ucfirst($this->oContainer->get('translator')->trans('comment')),
                    'required' => true,
                    'attr' => array('class' => 'form-control'),
                ))
                ->add("account_type", "entity", array(
                    'label' => ucfirst($this->oContainer->get('translator')->trans('account type')),
                    'required' => true,
                    'class' => 'AnalyzerBundle:AccountType',
                    'attr' => array('class' => 'form-control')
                ))
                ->add('save', "submit", array('attr' => array('class' => 'btn btn-primary')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AO\AnalyzerBundle\Entity\Account'
        ));
    }

    public function getName()
    {
        return 'ao_account';
    }

}
