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
        $fileFinder = new FileFinder;

        // none existent folder
        $path = __DIR__ . random_bytes(20);

        $this->expectException(RuntimeException::class);
        $files = $fileFinder->find($path);
    }

    public function testIfFileIsValid()
    {
        $fileFinder = new FileFinder;
        // file
        $file = new SplFileInfo($this->fileSystem->url('valid.json'));
        $this->assertTrue($fileFinder->isValidFile($file));

        // directory
        $file = new SplFileInfo($this->fileSystem->url('directory'));
        $this->assertTrue($fileFinder->isValidFile($file));
    }
}
