<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MarketingcontrollCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('marketingcontroll:send')
                ->setDescription('Controll sender')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $em = $this->getContainer()->get('doctrine')->getManager();
        $tasks = $em->getRepository('AppBundle:EmailTask')->findAll();
        foreach ($tasks as $task) {
            $done = $task->getDone();
            $counter = $task->getCounter();
            $leadnum = $task->getLeadnum();
            if ($done === 0) {
                if ($counter < $leadnum) {
                    $limit = 5;
                    $taskid = $task->getId();
                    $campid = $task->getCampId();
                    $settid = $task->getSettId();
                    $tempid = $task->getTempid();
                    $nextcons = $task->getNextcons();
                    $startid = $task->getStartid();
                    $counter = $task->getCounter();
                    $attid = $task->getAttid();
                    if ($attid === false) {
                        $attid = 0;
                    }
                    $output->writeln(sprintf('Running Cron Task <info>%s</info>', $taskid));
                    try {
                        $this->runCommand($campid, $counter, $limit, $settid, $tempid, $taskid, $leadnum, $attid, $nextcons);
                        $task->setCounter($counter + $limit);
                        $em->flush();
                    } catch (\Exception $e) {
                        $output->writeln('<error>ERROR</error>');
                    }
                } else {
                    $campid = $task->getCampId();
                    $elistupdate = $em->getRepository('AppBundle:Elist')
                            ->updateElistActive($campid);
                    $task->setDone(1);
                    $em->flush();
                }
            }
        }
        $output->writeln('<comment>Done!</comment>');
    }

    private function runCommand($campid, $counter, $limit, $settid, $tempid, $taskid, $leadnum, $attid, $nextcons) {
        $kernel = $this->getContainer()->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'marketing:send',
            'campid' => $campid,
            'offset' => $counter,
            'limit' => $limit,
            'settid' => $settid,
            'tempid' => $tempid,
            'taskid' => $taskid,
            'leadnum' => $leadnum,
            'attid' => $attid,
            'nextcons' => $nextcons,
        ));
        //var_dump($input);
        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        //$content = $output->fetch();

        return;
    }

}
