<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PhpImap\Mailbox;
use AppBundle\Entity\Maininbox;
use AppBundle\Entity\Mainattachment;
use AppBundle\Entity\Maincontact;

class MaindownloadCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('mainemail:download')
                ->setDescription('Controll download')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $em = $this->getContainer()->get('doctrine')->getManager();
        $settlist = $em->getRepository('AppBundle:Settings')->findallactiveIncoming('1');
        //var_dump($settlist);die;
        foreach ($settlist as $thisSettings) {
            $id = $thisSettings->getId();
            //var_dump($thisSettings);die;
            $downloadLimit = 30;
            $dirname = $thisSettings->getDirname();
            $directoryPath = $this->getContainer()->getParameter('kernel.root_dir') . '/../web/Files/' . $dirname . '/';
            $directoryPath = preg_replace("/app..../i", "", $directoryPath);
            //$incomingSsl = $thisSettings->getIncomingSSL();
            $imapServer = $thisSettings->getImapserver();
            $imapPort = $thisSettings->getImapport();
            $esuername = $thisSettings->getEusername();
            $epassword = $thisSettings->getEpassword();
            $lastemail = $thisSettings->getLastemail();
            //var_dump($lastemail);die;
            $connectionString = '{' . $imapServer . ':' . $imapPort . '}INBOX';
            //$connectionString = '{mail.dent1st.co.uk:143/imap/notls}INBOX';
            //var_dump($esuername);
            //var_dump($epassword);
            //var_dump($connectionString);die;

            $mailbox = new Mailbox($connectionString, $esuername, $epassword, $directoryPath);
            $mailsIds = $mailbox->searchMailBox('ALL');
            $spamfilter = $em->getRepository('AppBundle:Mainfilter')
                    ->findallspam();
            $myrulesFrom = $em->getRepository('AppBundle:Mainmessagerules')
                    ->selectallrules($id, 'from');
            $myrulesSubject = $em->getRepository('AppBundle:Mainmessagerules')
                    ->selectallrules($id, 'subject');
            $whitelist = array('zip', 'pdf', 'jpg', 'png', 'png', 'doc', 'docx', 'xls', 'xlsx', 'txt');
            //var_dump($myrulesfrom);
            //var_dump($myrulessubject);die;
            //var_dump($spamfilter);
            //die;
            //
            //var_dump($mailsIds);die;
            //die;
            //$mail = $mailbox->getMail($mailsIds[93]);
            //var_dump($mail);die;
            //$textHtml = strip_tags($mail->textHtml);
            //$att = $mail->getAttachments();
            /*
              foreach ($att as $at) {
              $attid = $at->id;
              var_dump($attid);
              }

              var_dump($att);
              die;
             * 
             */
            if (!$lastemail) {
                //$lastemail = $mailsIds[0];
                $firstKey = 0;
            } else {
                $firstKey = array_search($lastemail, $mailsIds) + 1;
            }
            //var_dump($lastKey);die;
            //var_dump($firstKey);die;

            $slicedMailsIds = array_slice($mailsIds, $firstKey, $downloadLimit);
            //var_dump($slicedMailsIds);
            //die;

            foreach ($slicedMailsIds as $mid) {
                $mailId = (int) $mid;
                $mail = $mailbox->getMail($mailId);
                //var_dump($mail);die;
                //$emailID = $mail->messageId;
                $textHtml = $mail->textHtml;
                $mycontent = $mail->textPlain;
                $contentclearedRaw = strip_tags($mycontent);
                $contentraw = str_replace('&nbsp;', '', $contentclearedRaw);
                $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $contentraw);

                //$messageId = $mail->messageId;
                $fromEMAIL = $mail->fromAddress;
                $toEMAIL = $mail->toString;
                $replyTO = $mail->replyTo;
                $toarray = $mail->to;
                $ccarray = $mail->cc;
                $fromNAME = $mail->fromName;
                if (!$content || $content === '') {
                    $textclearedRaw = strip_tags($textHtml);
                    $textcleared = str_replace('&nbsp;', '', $textclearedRaw);
                    $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $textcleared);
                }
                if ($fromNAME === '' || $fromNAME == false) {
                    $fromNAME = substr($fromEMAIL, 0, strpos($fromEMAIL, "@"));
                }
                $subject = $mail->subject;
                $mailDateString = $mail->date;
                $mailDate = new \DateTime($mailDateString);
                $maininbox = new Maininbox();

                //spam filter
                if ($spamfilter) {
                    foreach ($spamfilter as $spamtext) {
                        if ($spamtext['filtertext'] == $fromEMAIL) {
                            $maininbox->setFolder('Junk');
                            break;
                        }
                    }
                }
                //rule filter
                $checkfolder = $maininbox->getFolder();
                if (!$checkfolder) {
                    if ($myrulesFrom) {
                        foreach ($myrulesFrom as $myrule) {
                            if ($myrule['filtertext'] == $fromEMAIL) {
                                $maininbox->setFolder($myrule['folder']);
                                break;
                            }
                        }
                    }
                }
                $checkfolder2 = $maininbox->getFolder();
                if (!$checkfolder2) {
                    if ($myrulesSubject) {
                        foreach ($myrulesSubject as $myrule) {
                            if (strpos($subject, $myrule['filtertext']) !== false) {
                                $maininbox->setFolder($myrule['folder']);
                                break;
                            }
                        }
                    }
                }

                $checkfolder3 = $maininbox->getFolder();
                if (!$checkfolder3) {
                    $maininbox->setFolder('Inbox');
                }
                $maininbox->setSettings($thisSettings);
                $maininbox->setFromemail($fromEMAIL);
                $maininbox->setToemail($toEMAIL);
                $maininbox->setReplyto($replyTO);
                $maininbox->setToarray($toarray);
                $maininbox->setCcarray($ccarray);
                $maininbox->setFromname($fromNAME);
                $maininbox->setSubject($subject);
                $maininbox->setMailid($mailId);
                $maininbox->setSettid($id);
                $maininbox->setContent($content);
                if ($textHtml) {
                    $maininbox->setTexthtml($textHtml);
                }
                $maininbox->setCreatedAt(new \DateTime());
                $maininbox->setMaildate($mailDate);
                $maininbox->setPublicid($mailId);
                $maininbox->setSeen(0);
                $thisSettings->setLastemail($mailId);
                $thisSettings->setLastdownload(new \DateTime());
                $em->persist($thisSettings);
                $em->persist($maininbox);
                $em->flush();

                $attachments = $mail->getAttachments();
                if ($attachments) {
                    foreach ($attachments as $att) {

                        $attachmentStatus = false;
                        $fileName = $att->name;
                        $filePath = $att->filePath;
                        $extension = substr(strrchr($fileName, '.'), 1);
                        //var_dump($extension);
                        foreach ($whitelist as $whl) {
                            if ($extension === $whl) {
                                $attachmentStatus = true;
                                break;
                            }
                        }
                        if ($attachmentStatus === false) {
                            unlink($filePath);
                            $attpublicid = $att->id;
                            $mainattachment = new Mainattachment();
                            $mainattachment->setSettid($id);
                            $mainattachment->setEmailpublicid($mailId);
                            $mainattachment->setAttpublicid($attpublicid);
                            $mainattachment->setFileName('Attachment removed for security reasons!');
                            $mainattachment->setPath('removed');

                            $mainattachment->setCreatedAt(new \DateTime());
                            $mainattachment->setMaininbox($maininbox);
                            $maininbox->setAttachment(1);
                            $em->persist($mainattachment);
                            $em->persist($maininbox);
                            $em->flush();
                        } else {
                            if ($extension === 'zip') {
                                var_dump('this is zip, do something!');
                            }
                            $mainattachment = new Mainattachment();
                            $attpublicid = $att->id;
                            $filename = $att->name;
                            $fullpath = $att->filePath;
                            $path = substr($fullpath, strpos($fullpath, "Files") + 6);

                            $mainattachment->setSettid($id);
                            $mainattachment->setEmailpublicid($mailId);
                            $mainattachment->setAttpublicid($attpublicid);
                            $mainattachment->setFileName($filename);
                            $mainattachment->setPath($path);

                            $mainattachment->setCreatedAt(new \DateTime());
                            $mainattachment->setMaininbox($maininbox);
                            $maininbox->setAttachment(1);
                            $em->persist($mainattachment);
                            $em->persist($maininbox);
                            $em->flush();
                        }
                    }
                }
                //create new contact
                $contactemail = $em->getRepository('AppBundle:Maincontact')
                        ->findOneEmail($fromEMAIL);
                if (!$contactemail) {
                    $maincontact = new Maincontact();
                    $maincontact->setEmail($fromEMAIL);
                    $maincontact->setName($fromNAME);
                    $em->persist($maincontact);
                    $em->flush();
                }
            }
        }





        $output->writeln('<comment>Done!</comment>');
    }

}
