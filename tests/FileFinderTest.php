<?php
namespace Aecodes\Leap\Tests;

use Aecodes\Leap\FileFinder;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SplFileInfo;

class FileFinderTest extends TestCase
{

    public function setUp(): void
    {
        $directory = [
            'valid.json' => '{"VALID_KEY":123}',
            'directory'  => []
        ];
        // setup and cache the virtual file system
        $this->fileSystem = vfsStream::setup('root', 444, $directory);
    }

    public function testFolderMustExist()
    {
        $fileFinder = new FileFinder([]);

        // none existent folder
        $path = __DIR__ . random_bytes(20);

        $this->expectException(RuntimeException::class);
        $files = $fileFinder->find($path);
    }

    public function testGetAllFiles()
    {
        $fileFinder = new FileFinder([]);
        $files = $fileFinder->find($this->fileSystem->url());
        
        $files = iterator_to_array($files);
        
        $this->assertCount(1, $files);

        // generate random number of files
        $number = rand(0, 20);

        $dirFiles = array_fill(0, $number, random_bytes(10));

        $fs = vfsStream::setup('root', 444, $dirFiles);

        $files = iterator_to_array($fileFinder->find($fs->url()));
        $this->assertCount($number, $files);
    }

    public function testIfFileIsValid()
    {
        $fileFinder = new FileFinder([]);
        // file
        $file = new SplFileInfo($this->fileSystem->url('valid.json'));
        $this->assertTrue($fileFinder->isValidFile($file));
    }

    public function testIgnoresFilesInsideExcludedFolders()
    {
        // by default vendor and node_modules are ignored
        $fs = vfsStream::setup('root', 444, [
            'vendor'    =>  ['a', 'b', 'c'],
            'node_modules' => ['a', 'b', 'c'],
            'file',
            'file2',
            'folder' => ['a', 'b', 'c'],
        ]);

        $fileFinder = new FileFinder(['vendor', 'node_modules']);

        $files = iterator_to_array($fileFinder->find($fs->url()));
        $this->assertCount(5, $files);
    }
}
