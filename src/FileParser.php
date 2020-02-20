<?php

namespace Aecodes\Leap;

class FileParser
{
    protected $tasks = [];
    protected $keywords = [];

    public function __construct(array $keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Parse all files
     *
     * @param iterable $files
     * @return void
     */
    public function parseFiles(iterable $files)
    {
        if (empty($this->keywords)) {
            return;
        }

        foreach ($files as $file) {
            // getRealPath
            $this->parseFile($file->getPathName());
        }
    }

    /**
     * Parse the content of a file
     *
     * @param string $file
     * @return void
     */
    public function parseFile(string $file)
    {
        $content = file_get_contents($file);
        $lines = explode("\n", $content);

        for ($index = 0; $index < count($lines); $index++) {
            // line numbers are 1 based. index is 0 based
            $this->parseLine($file, $lines[$index], $index + 1);
        }
    }

    /**
     * Parse line
     *
     * @param string $file
     * @param string $line
     * @param int $lineNumber
     * @return void
     */
    public function parseLine(string $file, string $line, int $lineNumber)
    {
        if ($this->containTask($line)) {
            $this->registerTask($file, $line, $lineNumber);
        }
    }

    /**
     * check if line contains a task
     *
     * @param string $line
     * @return bool
     */
    public function containTask(string $line): bool
    {
        // if the line length is less than 4 character, no need to proceed.
        if (strlen($line) < 4) {
            return false;
        }

        // running stripos on all keywords
        $result = array_filter($this->keywords, function ($key) use ($line) {
            return stripos($line, $key) !== false;
        });

        return (count($result) > 0);
    }

    /**
     * Register task
     *
     * @param string $file
     * @param string $line
     * @param int $lineNumber
     * @return void
     */
    public function registerTask(string $file, string $line, int $lineNumber): void
    {
        $line = substr($line, 0, 100);
        $this->tasks[$file][] = new Task($line, $lineNumber);
    }

    /**
     * Get all registered tasks
     *
     * @return array
     */
    public function results(): array
    {
        return $this->tasks;
    }
}
