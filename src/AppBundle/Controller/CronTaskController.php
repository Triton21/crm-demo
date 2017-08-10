<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\CronTask;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class CronTaskController extends Controller {

    public function indexAction(request $request) {


        $crontask = new CronTask();

        $form = $this->createFormBuilder($crontask)
                ->add('name', 'text', array(
                    'label' => 'Template name', 'attr' => array('class' => 'input-sm')
                ))
                ->add('interval', 'integer', array(
                    'label' => 'Interval', 'attr' => array('class' => 'input-sm')
                ))
                ->add('commands', 'text', array(
                    // each item in the array will be an "email" field
                    
                    // these options are passed to each "email" type
                    'attr' => array(
                        'required' => false,
                        'attr' => array('class' => 'input-sm')
                    )
                ))
                ->add('save', 'submit', array(
                    'label' => 'save', 'attr' => array('class' => 'btn-success btn-md')))
                ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {

            return $this->render('AppBundle:Default:cronmain.html.twig', array(
                        'form' => $form->createView(), 'form' => $form->createView(),
            ));
        }

        return $this->render('AppBundle:Default:cronmain.html.twig', array(
                    'form' => $form->createView(), 'form' => $form->createView(),
        ));
    }

    function testAction() {
        $entity = new CronTask();

        $entity
                ->setName('Example asset symlinking task')
                ->setInterval(3600) // Run once every hour
                ->setCommands(array(
                    'assets:install --symlink web'
        ));

        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        return new Response('OK!');
    }
    
    function test2Action() {
        $entity = new CronTask();

        $entity
                ->setName('Example asset symlinking task')
                ->setInterval(3600) // Run once every hour
                ->setCommands(array(
                    'demo:greet'))
                ->setArg('Peter')
        ;

        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        return new Response('OK!');
    }

}
