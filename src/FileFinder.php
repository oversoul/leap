<?php
namespace Aecodes\Leap;

use SplFileInfo;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use RecursiveCallbackFilterIterator;

class FileFinder
{

    /**
     * Folders to be excluded
     *
     * @var array
     */
    protected $excludeFolders = ['vendor', 'node_modules'];

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
