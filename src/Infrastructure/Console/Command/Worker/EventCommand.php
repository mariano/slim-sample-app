<?php
namespace Infrastructure\Console\Command\Worker;

use Event\Event;
use Infrastructure\Queue\EventJob;
use Queue\EventQueueInterface;

class EventCommand extends WorkerCommand
{
    public function __construct(EventQueueInterface $queue)
    {
        parent::__construct();
        $this->queue = $queue;
    }

    protected function configure()
    {
        parent::configure();
        $this->setName('worker:event')
            ->setDescription('Process event jobs');
    }

    protected function work($job)
    {
        if (!($job instanceof EventJob)) {
            throw new InvalidArgumentException('Not an EventJob');
        }
        $this->process($job->getEvent());
    }

    private function process(Event $event)
    {
        echo "GOT EVENT!\n";
        var_dump($event);
        for ($i = 1; $i <= 10; $i++) {
            echo $i . " - ";
            sleep(1);
        }
        echo "\n";
    }
}