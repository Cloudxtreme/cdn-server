<?php

namespace CDNServer\Core\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('plainPassword', 'password', array('label' => 'Password'))
            ->add('stringRoles', 'choice', array(
                'choices'       => array('ROLE_ADMIN' => 'Admin', 'ROLE_MANAGER' => 'Manager', 'ROLE_USER' => 'User'),
                'placeholder'   => false,
            ))
            ->add('userGroups', 'entity', array(
                'class' => 'CDNServerCore:UserGroup',
                'placeholder' => false,
                'multiple'  => true,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CDNServer\Core\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cdnserver_corebundle_user';
    }
}
