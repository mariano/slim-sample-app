<?php
namespace Infrastructure\Console\Command\Worker;

use Application\Queue\QueueInterface;
use Disque\Queue\JobInterface;
use Exception;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class WorkerCommand extends Command
{
    /**
     * Disque queue
     *
     * @var QueueInterface
     */
    protected $queue;

    /**
     * Output stream
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * Do not process more than these number of jobs
     *
     * @var int
     */
    private $limit = 0;

    /**
     * Wether to allow any further job processing
     *
     * @var bool
     */
    private $allowJobs = true;

    /**
     * Create instance
     *
     * @param QueueInterface $queue Queue
     */
    public function __construct(QueueInterface $queue)
    {
        parent::__construct();
        $this->queue = $queue;
    }

    /**
     * Configure command
     *
     * @return void
     */
    protected function configure()
    {
        $this->addOption(
            'limit',
            null,
            InputOption::VALUE_OPTIONAL,
            'If set, do not process more than these number of jobs. Set to 0 for no limit. Defaults to 0',
            0
        );
    }

    /**
     * Initializes the command just after the input has been validated.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $this->setProcessTitle('[worker] ' . $this->getName());

        declare(ticks = 30);

        foreach ([SIGTERM] as $signal) {
            if (!pcntl_signal($signal, [$this, 'signal'])) {
                throw new RuntimeException("Could not register signal {$signal}");
            }
            $this->out("Registered for signal {$signal}", OutputInterface::VERBOSITY_VERY_VERBOSE);
        }
    }

    /**
     * Signal handler. Needs to be public
     *
     * @param string $signal Signal
     * @return void
     */
    public function signal($signal)
    {
        switch ($signal) {
            case SIGTERM:
                $this->out('Received signal to shutdown...', OutputInterface::VERBOSITY_VERBOSE);
                $this->allowJobs = false;
                break;
        }
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = $input->getOption('limit');
        if (!is_numeric($limit) || $limit < 0) {
            throw new InvalidArgumentException('Limit should be set to the maximum number of jobs to process, or 0 for no limit');
        }
        $this->limit = $limit ? (int) $limit : 0;

        $jobs = 0;
        $queueName = $this->queue->getName();

        $this->out("Waiting on {$queueName} jobs...");
        while ($this->allowJobs) {
            $job = $this->queue->get();

            $this->out("Got job #{$job->getId()}", OutputInterface::VERBOSITY_VERBOSE);
            $this->out("Job #{$job->getId()} body: " . json_encode($job->getBody()), OutputInterface::VERBOSITY_VERY_VERBOSE);

            try {
                $this->work($job);
                $this->queue->processed($job);
                $this->out("Finished processing job #{$job->getId()}", OutputInterface::VERBOSITY_VERBOSE);
            } catch (Exception $e) {
                $this->out('ERROR ' . get_class($e) . ' while processing job: ' . $e->getMessage());
                $this->out("ERROR Stacktrace: \n\t" . trim(str_replace("\n", "\n\t", $e->getTraceAsString())), OutputInterface::VERBOSITY_VERY_VERBOSE);
            }

            $this->out("Waiting on {$queueName} jobs...");

            $jobs++;
            if ($limit > 0 && $jobs === $limit) {
                $this->allowJobs = false;
            }
        }
        $this->out("TOTAL jobs processed: {$jobs}");
    }

    /**
     * Output the given string using the given log level
     *
     * @param string $text Text to output
     * @param int $level Log level of message
     */
    protected function out($text, $level = OutputInterface::VERBOSITY_NORMAL)
    {
        if ($this->output->isQuiet() || $level > $this->output->getVerbosity()) {
            return;
        }

        $this->output->writeln('[' . date('Y-m-d H:i:s') . '] ' . $text);
    }

    /**
     * Work on a job
     *
     * @param JobInterface $job Job
     * @return void
     */
    abstract protected function work(JobInterface $job);
}