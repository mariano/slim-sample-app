<?php
namespace Application\Command\Bus;

use Application\Command\CommandInterface;

interface CommandBusInterface
{
    /**
     * Execute a command
     *
     * @param CommandInterface $command Command
     * @return void
     */
    public function execute(CommandInterface $command);
}