<?php

namespace LoginBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class ContactCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('contact:send')
                ->setDescription('Send contact email')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $contacts = $em->getRepository('LoginBundle:Contact')->findBy(array('sent' => 0));
        $demos = $em->getRepository('LoginBundle:Demo')->findBy(array('sent' => 0));

        if ($contacts) {
            foreach ($contacts as $contact) {
                $parameterPath = $this->getContainer()->get('kernel')->getRootDir() . '/config/emailParameters.yml';
                $value = Yaml::parse(file_get_contents($parameterPath));
                $euser = $value['parameters']['sender-name'];
                $email = $value['parameters']['email'];
                $epass = $value['parameters']['password'];
                $messagesend = 'New contact request received. Name: ' . $contact->getName() . ' Email:' . $contact->getEmail() . ' Phone:' . $contact->getPhone() . ' Message: ' . $contact->getMessage();
                $subject = 'New contact request received. Name: ' . $contact->getName();

                $transporter = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
                        ->setUsername($email)
                        ->setPassword($epass);

                $mailer = \Swift_Mailer::newInstance($transporter);

                $message = \Swift_Message::newInstance($subject)
                        ->setFrom(array($email => $euser))
                        ->setTo(array($email => $euser))
                        ->setBody($messagesend, 'text/html')
                ;

                $mailer->getTransport()->start();
                $mailer->send($message);
                $mailer->getTransport()->stop();

                $contact->setSent(1);
                $em->persist($contact);
                $em->flush();

                $output->writeln($contact->getName() . ' Contact received');
            }
        } else {
            $output->writeln('No new contact');
        }
        if ($demos) {
            foreach ($demos as $demo) {
                $parameterPath = $this->getContainer()->get('kernel')->getRootDir() . '/config/emailParameters.yml';

                $value = Yaml::parse(file_get_contents($parameterPath));
                $euser = $value['parameters']['sender-name'];
                $email = $value['parameters']['email'];
                $epass = $value['parameters']['password'];
                $messagesend = 'New demo request received. Name: ' . $demo->getName() . ' Email:' . $demo->getEmail() . ' Phone:' . $demo->getPhone();
                $subject = 'New demo request received. Name: ' . $demo->getName();

                $transporter = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
                        ->setUsername($email)
                        ->setPassword($epass);

                $mailer = \Swift_Mailer::newInstance($transporter);

                $message = \Swift_Message::newInstance($subject)
                        ->setFrom(array($email => $euser))
                        ->setTo(array($email => $euser))
                        ->setBody($messagesend, 'text/html')
                ;

                $mailer->getTransport()->start();
                $mailer->send($message);
                $mailer->getTransport()->stop();

                $demo->setSent(1);
                $em->persist($demo);
                $em->flush();

                $output->writeln($demo->getName() . ' Demo received');
            }
        } else {
            $output->writeln('No new demo');
        }
    }

}
