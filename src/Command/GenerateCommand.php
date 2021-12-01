<?php declare(strict_types = 1);

namespace Everon\PackageGenerator\Command;

use Everon\PackageGenerator\Configurator\PackagerConfigurator;
use Everon\PackageGenerator\PackagerDefinitionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends AbstractCommand
{
    public const COMMAND_NAME = 'generate';

    public const COMMAND_DESCRIPTION = 'Generate package files';

    public const OPTION_SCHEMA_FILENAME = 'schemaFilename';

    public const OPTION_OUTPUT_PATH = 'outputPath';

    public const OPTION_OUTPUT_OVERWRITE_EXISTING_FILES = 'overwriteExistingFiles';

    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setDefinition(
                [
                    new InputOption(
                        static::OPTION_SCHEMA_FILENAME,
                        's',
                        InputOption::VALUE_REQUIRED,
                        'Path to schema file',
                        null
                    ),
                    new InputOption(
                        static::OPTION_OUTPUT_PATH,
                        'o',
                        InputOption::VALUE_REQUIRED,
                        'Output path',
                        null
                    ),
                    new InputOption(
                        static::OPTION_OUTPUT_OVERWRITE_EXISTING_FILES,
                        'f',
                        InputOption::VALUE_REQUIRED,
                        'Set to true, to overwrite existing files',
                        false
                    ),
                ]
            );
    }

    protected function buildConfigurator(InputInterface $input): PackagerConfigurator
    {
        return (new PackagerConfigurator())
            ->setSchemaFilename(
                $input->hasOption(static::OPTION_SCHEMA_FILENAME) ? $input->getOption(
                    static::OPTION_SCHEMA_FILENAME
                ) : null
            )->setOutputPath(
                $input->hasOption(static::OPTION_OUTPUT_PATH) ? $input->getOption(static::OPTION_OUTPUT_PATH) : null
            )
            ->setShouldOverwriteExistingFiles(
                (bool) ($input->hasOption(static::OPTION_OUTPUT_OVERWRITE_EXISTING_FILES) ? $input->getOption(static::OPTION_OUTPUT_OVERWRITE_EXISTING_FILES) : false)
            );
    }

    protected function executeCommand(InputInterface $input, OutputInterface $output): int
    {
        $configurator = $this->buildConfigurator($input);
        $configurator = $this->facade->buildSchema($configurator);
        $results = $this->facade->generate($configurator);

        foreach ($results as $item) {
            if ($item->getType() === PackagerDefinitionInterface::CONFIGURATION_SCHEMA_RESOURCE_TYPE_DIR) {
                continue;
            }

            $generated = $item->wasGenerated() ? 'Generated' : 'Skipped (exists)';

            $output->writeln(
                sprintf('%s <fg=yellow>%s</> -> <fg=green>%s</>', $generated, $item->getResourceName(), $item->getPath())
            );
        }

        $output->writeln('All done.');

        return 0;
    }
}
