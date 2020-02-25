<?php

namespace Aecodes\Leap;

class Config
{

    protected $config = [];
    protected $defaultConfig = [
        'exclude_folders' => ['vendor', 'node_modules', '.leap'],
        'keywords' => ['FIXME', 'NOTE'],
    ];

    protected $directory;

    public function __construct(string $directory)
    {
        $this->directory = $directory;
        // load the config
        $this->load();
    }

    protected function getConfigFromFile($configFile):  ? array
    {
        if (!file_exists($configFile)) {
            return null;
        }

        $config = require $configFile;
        $config['exclude_folders'][] = '.leap';
        return $config;
    }

    protected function getLocalConfig() :  ? array
    {
        $configFile = $this->directory . DIRECTORY_SEPARATOR . '.leap' . DIRECTORY_SEPARATOR . 'config.php';
        return $this->getConfigFromFile($configFile);
    }

    protected function getGlobalConfig() :  ? array
    {
        $configFile = getenv('HOME') . DIRECTORY_SEPARATOR . '.leap' . DIRECTORY_SEPARATOR . 'config.php';
        return $this->getConfigFromFile($configFile);
    }

    protected function load()
    {
        $localConfig = $this->getLocalConfig();
        $globalConfig = $this->getGlobalConfig();

        if ($localConfig) {
            $this->config = $localConfig;
            return $this;
        }

        if ($globalConfig) {
            $this->config = $globalConfig;
            return $this;
        }

        return $this;
    }

    public function get($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return $this->defaultConfig[$key];
    }
}
