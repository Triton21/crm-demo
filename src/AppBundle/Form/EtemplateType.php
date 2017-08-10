<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EtemplateType extends AbstractType {

    protected $attachmentsarray;

    function __construct($attachmentsarray) {
        $this->attachmentsarray = $attachmentsarray;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('tempname', 'text', array(
                    'label' => 'Template name',))
                ->add('subject')
                ->add('body', 'textarea', array('attr' => array('class' => 'ckeditor')))
                ->add('attach1', 'choice', array(
                    'label' => 'Attachment 1',
                    'choices' => $this->attachmentsarray, 'attr' => array('class' => 'input-sm')))
                ->add('attach2', 'choice', array(
                    'label' => 'Attachment 2',
                    'choices' => $this->attachmentsarray, 'attr' => array('class' => 'input-sm')))
                ->add('attach3', 'choice', array(
                    'label' => 'Attachment 3',
                    'choices' => $this->attachmentsarray, 'attr' => array('class' => 'input-sm')))
                ->add('save', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Etemplate'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'appbundle_etemplate';
    }

}
