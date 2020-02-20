<?php
namespace Aecodes\Leap\Tests;

use Aecodes\Leap\FileParser;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FileParserTest extends TestCase {
    

    public function testContainTask()
    {
        $parser = new FileParser(['FIXME', 'NOTE']);
        
        $result = $parser->containTask('hello world // Note: this is a task');
        $this->assertTrue($result);

        $result = $parser->containTask('hello world // NOTE: this is a task');
        $this->assertTrue($result);
        
        $result = $parser->containTask('hello world // Not');
        $this->assertFalse($result);

                
        $result = $parser->containTask('hello world // FIXME: fix this');
        $this->assertTrue($result);
    }

    public function testCanParseFile()
    {
        $keywords = ['FIXME', 'NOTE'];
        $directory = [
            'file.php' => '<?php 
                // Note: update this file
            ',
            'second-file.js' => '
                // FIXME: fix this function
                doSomeStuff() {

                }

                // FIXME: whaaatever
            ',
            'no-task-file' => '
                lorem
                ipsum
            '
        ];
        // setup and cache the virtual file system
        $fs = vfsStream::setup('root', 444, $directory);

        $parser = new FileParser($keywords);
        $filePath = $fs->url() . '/file.php';
        $parser->parseFile($filePath);
        $this->assertArrayHasKey($filePath, $parser->results());
        $this->assertCount(1, $parser->results()[$filePath]);
        
        $parser = new FileParser($keywords);
        $filePath = $fs->url() . '/second-file.js';
        $parser->parseFile($filePath);
        $this->assertArrayHasKey($filePath, $parser->results());
        $this->assertCount(2, $parser->results()[$filePath]);
        
        $parser = new FileParser($keywords);
        $filePath = $fs->url() . '/no-task-file';
        $parser->parseFile($filePath);
        $this->assertArrayNotHasKey($filePath, $parser->results());
    }

}