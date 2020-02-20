<?php

namespace Aecodes\Leap;

class Config {
    
    protected $config = [];
    protected $defaultConfig = [
        'exclude_folders'   =>  ['vendor', 'node_modules', '.leap'],
        'keywords'          =>  ['FIXME', 'NOTE'],
    ];

    protected $directory;

    public function __construct(string $directory) {
        $this->directory = $directory;
        // load the config
        $this->load();
    }

    protected function load()
    {
        $configFile = $this->directory . DIRECTORY_SEPARATOR . '.leap' . DIRECTORY_SEPARATOR . 'config.php';

        if ( ! file_exists($configFile) ) {
            return $this;
        }

        $this->config = require $configFile;
        $this->config['exclude_folders'][] = '.leap';
    }

    public function get($key)
    {
        if ( isset($this->config[$key]) ) {
            return $this->config[$key];
        }

        return $this->defaultConfig[$key];
    }
}