<?php
namespace Infrastructure\Console\Command\Worker;

use Disque\Queue\JobInterface;
use Domain\Event\EventInterface;
use Infrastructure\Queue\EventJob;
use Queue\EventQueueInterface;

class EventCommand extends WorkerCommand
{
    /**
     * Configure command
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('worker:event')
            ->setDescription('Process event jobs');
    }

    /**
     * Work on a job
     *
     * @param JobInterface $job Job
     * @return void
     */
    protected function work(JobInterface $job)
    {
        if (!($job instanceof EventJob)) {
            throw new InvalidArgumentException('Not an EventJob');
        }
        $this->process($job->getName(), $job->getEvent());
    }

    /**
     * Process event
     *
     * @param string $name Event name
     * @param EventInterface $event Event
     * @return void
     */
    private function process($name, EventInterface $event)
    {
        echo "GOT EVENT!\n";
        var_dump(compact('name', 'event'));
        for ($i = 1; $i <= 10; $i++) {
            echo $i . " - ";
            sleep(1);
        }
        echo "\n";
    }
}