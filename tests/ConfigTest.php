<?php
namespace Aecodes\Leap\Tests;

use Aecodes\Leap\Config;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{

    public function testDefaultConfig()
    {
        // no config here
        $config = new Config('./');

        $this->assertEquals(['vendor', 'node_modules', '.leap'], $config->get('exclude_folders'));
        $this->assertEquals(['FIXME', 'NOTE'], $config->get('keywords'));
    }

    public function testCanConfigureExcludedFolders()
    {
        $directory = [
            ".leap" => [
                "config.php"    =>  "<?php
                    return [
                        'exclude_folders'  =>  [],
                        'keywords'  =>  ['FIXME', 'NOTE']
                    ];
                "
            ]
        ];

        $fs = vfsStream::setup('home', 444, $directory);

        $config = new Config($fs->url() . '/');

        $this->assertEquals(['.leap'], $config->get('exclude_folders'));
        $this->assertEquals(['FIXME', 'NOTE'], $config->get('keywords'));
    }

    public function testCanConfigureKeywords()
    {
        $directory = [
            ".leap" => [
                "config.php"    =>  "<?php
                    return [
                        'exclude_folders'  =>  ['vendor', 'node_modules'],
                        'keywords'  =>  ['FIXME', 'NOTE', 'TODO'],
                    ];
                "
            ]
        ];

        $fs = vfsStream::setup('home', 444, $directory);

        $config = new Config($fs->url() . '/');

        $this->assertEquals(['FIXME', 'NOTE', 'TODO'], $config->get('keywords'));
    }
}