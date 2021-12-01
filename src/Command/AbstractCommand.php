<?php declare(strict_types = 1);

namespace Everon\PackageGenerator\Command;

use Everon\PackageGenerator\PackagerDefinitionInterface;
use Everon\PackageGenerator\PackagerFacade;
use Everon\PackageGenerator\PackagerFacadeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

abstract class AbstractCommand extends Command
{
    protected const COMMAND_NAME = 'unknown';

    protected const COMMAND_DESCRIPTION = 'unknown';

    protected PackagerFacadeInterface $facade;

    abstract protected function executeCommand(InputInterface $input, OutputInterface $output): int;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->facade = new PackagerFacade();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(sprintf('<fg=yellow>EveronPackager</> <fg=green>v%s</>', PackagerDefinitionInterface::VERSION));

        try {
            return $this->executeCommand($input, $output);
        } catch (Throwable $exception) {
            $output->writeln(sprintf('<fg=red>ERROR</> <fg=white>%s</>', $exception->getMessage()));
            return 1;
        }
    }
}
