<?php

namespace LoginBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('firstname', 'text', array(
                    'required' => true,
                    'attr' => array(
                        'placeholder' => 'First Name'
                    )
                ))
                ->add('lastname', 'text', array(
                    'required' => true,
                    'attr' => array(
                        'placeholder' => 'Last Name'
                    )
                ))
                ->add('email', 'email', array(
                    'label' => 'Email',
                    'required' => true,
                    'attr' => array(
                        'placeholder' => 'Email address'
                    )
                ))
                ->add('phone', 'text', array(
                    'required' => true,
                    'attr' => array(
                        'placeholder' => 'Phone number'
                    )
                ))
                ->add('gdc', 'integer', array(
                    'label' => 'Gdc no',
                    'required' => true,
                    'attr' => array(
                        'placeholder' => 'GDC number'
                    )
                ))
                ->add('practice', 'text', array(
                    'label' => 'Practice',
                    'attr' => array(
                        'placeholder' => 'Practice name'
                    )
                ))
                ->add('address', 'text', array(
                    'label' => 'Address',
                    'attr' => array(
                        'placeholder' => 'Practice address'
                    )
                ))
                ->add('username', 'text', array(
                    'required' => true,
                    'attr' => array(
                        'placeholder' => 'Choose a username'
                    )
                ))
                ->add('password', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'The password fields must match.',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => true,
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                ))
                ->add('isActive', 'checkbox', array(
                    'label' => 'Active',
                    'required' => false,
                ))
                ->add('isAdmin', 'checkbox', array(
                    'label' => 'Make admin',
                    'required' => false,
                ))
                ->add('isSuperAdmin', 'checkbox', array(
                    'mapped' => false,
                    'label' => 'Make super admin',
                    'required' => false,
                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LoginBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'loginbundle_user';
    }

}
