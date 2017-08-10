<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PricelistType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('category')
                ->add('code')
                ->add('priceName')
                ->add('description')
                ->add('displayPrice')
                ->add('price')
                ->add('active', 'choice', array(
                    'label' => 'Active',
                    'choices' => array(1 => 'active', 0 => 'not active',), 'attr' => array('class' => 'input-sm')
                ))
                ->add('save', 'submit', array(
                    'label' => 'save', 'attr' => array('class' => 'btn-success btn-md')))

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Pricelist'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'appbundle_pricelist';
    }

}
