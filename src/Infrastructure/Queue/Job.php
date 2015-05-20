<?php
namespace Infrastructure\Queue;

use Queue\JobInterface;
use Disque\Queue\Job as DisqueJob;
use Disque\Queue\JobInterface as DisqueJobInterface;

class Job extends DisqueJob implements JobInterface, DisqueJobInterface
{
}