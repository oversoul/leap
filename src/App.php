<?php
namespace Aecodes\Leap;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class App extends Command
{

    /**
     * @var string
     */
    protected static $defaultName = 'find';

    protected function configure()
    {
        $this
            ->setDescription('Find your tasks.')
            ->setHelp('Leap helps you find easily your tasks [FIXME, ...].')
            ->setDefinition(
                new InputDefinition([
                    new InputArgument('path', InputArgument::OPTIONAL, 'The path'),
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path') ?? getcwd();

        // get the config
        $config = new Config($path);

        // proceed with the current/default config
        $fileFinder = new FileFinder($config->get('exclude_folders'));
        $fileParser = new FileParser($config->get('keywords'));

        $files = $fileFinder->find($path);

        $fileParser->parseFiles($files);

        $results = $fileParser->results();

        $this->renderResults($results, $output);

        return 0;
    }

    public function renderResults($results, OutputInterface $output)
    {
        $output->writeln('');

        if (count($results) === 0) {
            $output->writeln("<error>No tasks found.</error>\n");
            return;
        }

        foreach ($results as $file => $tasks) {
            $output->writeln("<options=underscore>{$file}</>\n");
            foreach ($tasks as $task) {
                $output->writeln("<comment>{$task->renderLine()}</comment><info>{$task->getLine()}</info>");
            }
            $output->writeln('');
        }
    }

}
