<?php
namespace Aecodes\Leap;

use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class FileFinder
{

    /**
     * Folders to be excluded
     *
     * @var array
     */
    protected $excludeFolders = [];

    public function __construct(array $excludeFolders)
    {
        $this->excludeFolders = $excludeFolders;
    }

    /**
     * Find files in path
     *
     * @param string $path
     * @return RecursiveIteratorIterator
     */
    public function find(string $path): RecursiveIteratorIterator
    {
        $iterator = new RecursiveDirectoryIterator(
            $path,
            RecursiveDirectoryIterator::SKIP_DOTS
        );

        $callbackIterator = new RecursiveCallbackFilterIterator($iterator, [$this, 'isValidFile']);

        return new RecursiveIteratorIterator($callbackIterator);
    }

    /**
     * Check if file is valid
     *
     * @param SplFileInfo $file
     * @return boolean
     */
    public function isValidFile(SplFileInfo $file): bool
    {
        return $file->isFile() || !in_array($file->getBaseName(), $this->excludeFolders);
    }
}
