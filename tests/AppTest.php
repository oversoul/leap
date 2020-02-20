<?php
namespace Aecodes\Leap\Tests;

use Aecodes\Leap\App;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class AppTest extends TestCase
{

    protected $fs;

    public function setUp(): void
    {
        $directory = [
            '.leap' => [
                'config.php' => "<?php
                return [
                    'keywords'  =>  ['NOTE', 'TODO', 'FIXME'],
                ];
                ",
            ],
            'file.php' => '<?php
                // Note: update this file
            ',
            'second-file.js' => '
                // FIXME: fix this function
                doSomeStuff() {

                }

                // FIXME: whaaatever
            ',
            'task-file' => '
                TODO hello world
                lorem
                ipsum
            ',
        ];
        // setup and cache the virtual file system
        $this->fs = vfsStream::setup('root', 444, $directory);
    }

    public function testGetExpectedOutput()
    {
        $application = new Application();
        $application->add(new App());

        $command = $application->find('find');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'path' => $this->fs->url(),
        ]);

        $output = $commandTester->getDisplay();

        $expectedOutput = [
            'vfs://root/file.php',
            'line 2      // Note: update this file',
            'vfs://root/second-file.js',
            'line 2      // FIXME: fix this function',
            'line 7      // FIXME: whaaatever',
            'vfs://root/task-file',
            'line 2      TODO hello world',
        ];

        foreach ($expectedOutput as $lineOutput) {
            $this->assertStringContainsString($lineOutput, $output);
        }
    }
}
