<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Esent;

class MarketingCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('marketing:send')
                ->setDescription('Send newsletter')
                ->addArgument(
                        'campid', InputArgument::OPTIONAL, 'campid'
                )->addArgument(
                        'offset', InputArgument::OPTIONAL, 'offset'
                )
                ->addArgument(
                        'limit', InputArgument::OPTIONAL, 'limit'
                )
                ->addArgument(
                        'settid', InputArgument::OPTIONAL, 'settid'
                )
                ->addArgument(
                        'tempid', InputArgument::OPTIONAL, 'tempid'
                )
                ->addArgument(
                        'taskid', InputArgument::OPTIONAL, 'taskid'
                )
                ->addArgument(
                        'leadnum', InputArgument::OPTIONAL, 'leadnum'
                )
                ->addArgument(
                        'attid', InputArgument::OPTIONAL, 'attid'
                )
                ->addArgument(
                        'nextcons', InputArgument::OPTIONAL, 'nextcons'
                )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $offset = $input->getArgument('offset');
        $limit = $input->getArgument('limit');
        $campid = $input->getArgument('campid');
        $settid = $input->getArgument('settid');
        $tempid = $input->getArgument('tempid');
        $taskid = $input->getArgument('taskid');
        $leadnum = $input->getArgument('leadnum');
        $attid = $input->getArgument('attid');
        $nextcons = $input->getArgument('nextcons');
        $em = $this->getContainer()->get('doctrine')->getManager();

        // FIND SETTINGS
        $thissettings = $em->getRepository('AppBundle:Settings')
                ->findOneBy(array('id' => $settid));
        $fromname = $thissettings->getFromname();
        $thistemplate = $em->getRepository('AppBundle:ElistTemplate')
                ->findOneBy(array('id' => $tempid));
        //FIND CAMPAIGN
        $restnum = $offset + $limit;
        //var_dump($restnum);die;
        if ($leadnum < $limit) {
            $limit = $leadnum;
        } else {
            if ($leadnum < $restnum) {
                $limit = $limit - ($restnum - $leadnum);
                //var_dump($limit);
                //die;
            }
        }
        //var_dump($leadnum);
        $mycontacts = $em->getRepository('AppBundle:Elist')
                ->findrange($campid, $limit, $offset);
        //var_dump(count($mycontacts));die;
        if ($attid != 0) {
            $thisattach = $em->getRepository('AppBundle:Product')
                    ->findOneBy(array('id' => $attid));
            $attachname = $thisattach->getImageName();
            $em->flush();
        } else {
            $attachname = false;
            $thisattach = false;
        }
        $path = false;
        if ($thisattach) {
            $attName = $thisattach->getImageName();
            $attPath = $thisattach->getPath();
            $path = $attPath . $attName;
        }


        $subject = $thistemplate->getSubject();
        $oldbody = $thistemplate->getBody();
        if (strpos($oldbody, '%consdate%') != false) {
            $newbody = str_replace('%consdate%', $nextcons, $oldbody);
        } else {
            $newbody = $oldbody;
        }

        $smtp = $thissettings->getSmtp();
        $port = $thissettings->getPort();
        $mssl = $thissettings->getEssl();
        $euser = $thissettings->getEusername();
        $epass = $thissettings->getEpassword();
        $auth = $thissettings->getAuth();

        if ($auth) {

            $transport = \Swift_SmtpTransport::newInstance($smtp, $port)
                    ->setUsername($euser)
                    ->setPassword($epass)
                    ->setAuthMode('PLAIN')
            ;
        } else {
            $transport = \Swift_SmtpTransport::newInstance($smtp, $port, $mssl)
                    ->setUsername($euser)
                    ->setPassword($epass)
                    ->setAuthMode('PLAIN')
            ;
        }

        $mailer = \Swift_Mailer::newInstance($transport);

        $mailer->getTransport()->start();

        foreach ($mycontacts as $my) {
            $firstname = $my->getFirstname();
            $fullname = $my->getCustomerName();
            if ($firstname) {
                if (strpos($newbody, '%firstname%') != false) {
                    $body = str_replace('%firstname%', $firstname, $newbody);
                } else {
                    $body = $newbody;
                }
            } else {
                if (strpos($newbody, '%firstname%') != false) {
                    $body = str_replace('%firstname%', $fullname, $newbody);
                } else {
                    $body = $newbody;
                }
            }

            $email = $my->getEmail();
            //var_dump($email);
            $message = \Swift_Message::newInstance($subject)
                    ->setFrom(array($euser => $fromname))
                    ->setTo(array($email => 'Client'))
                    ->setBody($body, 'text/html')
            ;
            if ($path) {
                $message->attach(\Swift_Attachment::fromPath($path));
            }
            $mailer->send($message);
            $my->setActive(1);
            $esent = new Esent();
            $esent->setCampId($campid);
            $esent->setTaskId($taskid);
            $esent->setTempId($tempid);
            $esent->setEmail($email);
            $esent->setCreatedAt(new \DateTime());
            if ($fullname) {
                $esent->setFullName($fullname);
            }
            $esent->setElist($my);
            $em->persist($esent);
            $em->persist($my);
            $em->flush();
        }


        $mailer->getTransport()->stop();

        $text = 'Email sent!';



        $output->writeln($text);
    }

}
