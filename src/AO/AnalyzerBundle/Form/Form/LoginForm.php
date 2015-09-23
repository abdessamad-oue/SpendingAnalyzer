<?php

namespace AO\AnalyzerBundle\Form\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of loginType
 *
 * @author Abdessamad OUERYEMCHI 
 */
class LoginForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        
        $builder->add('username', 'text', array('required' => true, 'trim' => true, 'max_length' => 50));
        $builder->add('password', 'password', array('required' => true, 'max_length' => 20));

    }

    public function getName()
    {
        return '';

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'intention' => 'authentication'
        ));

    }

}
