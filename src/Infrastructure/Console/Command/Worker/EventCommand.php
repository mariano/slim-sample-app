<?php
namespace Infrastructure\Console\Command\Worker;

use Disque\Queue\Job;

class EventCommand extends WorkerCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('worker:event')
            ->setDescription('Process event jobs');
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