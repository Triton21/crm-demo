<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
//use AppBundle\Entity\Settings;
use AppBundle\Model\MaininboxSearch;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class MaininboxSearchType extends AbstractType {

    protected $accountsarray;

    public function __construct($accountsarray) {
        $this->accountsarray = $accountsarray;
    }

    /*
      public function listener($accountsarray) {
      $this->accountsarray = $accountsarray;
      }
     * 
     */

    protected $perPage;
    protected $perPageChoices = array(10, 20, 40);

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $perPageChoices = array();
        foreach ($this->perPageChoices as $choice) {
            $perPageChoices[$choice] = 'Display ' . $choice . ' items';
        }
        $builder

                //old form fields
                ->add('settings', 'choice', array(
                    'label' => 'Account',
                    'choices' => $this->accountsarray, 'attr' => array('class' => 'input-sm')))
                ->add('texthtml', 'text', array(
                    'label' => 'Keyword',
                ))
                ->add('dateFrom', 'date', array(
                    'required' => false,
                    'widget' => 'single_text', 'attr' => array('class' => 'input-sm')
                ))
                ->add('dateTo', 'date', array(
                    'required' => false,
                    'widget' => 'single_text', 'attr' => array('class' => 'input-sm')
                ))
                ->add('sort', 'hidden', array(
                    'required' => false,
                ))
                ->add('direction', 'hidden', array(
                    'required' => false,
                ))
                ->add('page', 'hidden', array(
                    'required' => false,
                ))
                ->add('sortSelect', 'choice', array(
                    'choices' => MaininboxSearch::$sortChoices,
                ))
                ->add('perPage', 'choice', array(
                    'choices' => $perPageChoices,
                ))
                ->add('search', 'submit', array(
                    'attr' => array(
                        'class' => 'btn btn-primary',
                    )
                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Model\MaininboxSearch'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'appbundle_maininboxsearch';
    }

}
