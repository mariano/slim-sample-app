<?php
namespace Application\Command\Handler;

use Application\Command\CommandInterface;

interface CommandHandlerInterface
{
    /**
     * Handle a command
     *
     * @param CommandInterface $command Command
     * @return void
     */
    public function handle(CommandInterface $command);
}