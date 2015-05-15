<?php
namespace Infrastructure\Console\Command\Worker;

use Disque\Queue\Job;

class EmailCommand extends WorkerCommand
{
    protected function getQueueName()
    {
        return 'emails';
    }

    protected function configure()
    {
        parent::configure();
        $this->setName('worker:email')
            ->setDescription('Process email jobs');
    }

    protected function work(Job $job)
    {
        echo "GOT JOB!\n";
        var_dump($job);
        for ($i = 1; $i <= 10; $i++) {
            echo $i . " - ";
            sleep(1);
        }
        echo "\n";
    }
}