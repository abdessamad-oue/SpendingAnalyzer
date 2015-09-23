<?php

namespace AO\AnalyzerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use \Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TransactionType
 *
 * @author Abdessamad OUERYEMCHI. 
 */
class TransactionType extends AbstractType
{

    private $oContainer;

    public function __construct(Container $oContainer)
    {
        $this->oContainer = $oContainer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add("account", "entity", array(
                    'required' => true,
                    'label' => ucfirst($this->oContainer->get('translator')->trans('account')),
                    'class' => 'AnalyzerBundle:Account',
                    'attr' => array('class' => 'form-control'),
                ))
                ->add("category", "entity", array(
                    'label' => ucfirst($this->oContainer->get('translator')->trans('category')),
                    'required' => true,
                    'class' => 'AnalyzerBundle:Category',
                    'translation_domain' => 'messages',
                    'attr' => array('class' => 'form-control'),
                ))
                ->add("date", "date", array(
                    'label' => ucfirst($this->oContainer->get('translator')->trans('date')),
                    'required' => true,
                    'widget' => 'single_text',
                    'constraints' => array(
                        new Assert\Date(),
                        new Assert\NotBlank()
                    ),
                    'attr' => array('class' => 'form-control')
                ))
                ->add("amount", "number", array(
                    'label' => ucfirst($this->oContainer->get('translator')->trans('amount')),
                    'required' => true,
                    'constraints' => array(
                         new Assert\NotBlank()
                    ),
                    'attr' => array('class' => 'form-control')
                ))
                ->add("wording", "text", array(
                    'label' => ucfirst($this->oContainer->get('translator')->trans('wording')),
                    'required' => true,
                    'constraints' => array(
                         new Assert\NotBlank()
                    ),
                    'attr' => array('class' => 'form-control')
                ))
                ->add('save', "submit", array('attr' => array('class' => 'btn btn-primary')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AO\AnalyzerBundle\Entity\Transaction'
        ));
    }

    public function getName()
    {
        return 'ao_trans';
    }

}
