<?php

class ConfigManager
{
    public function setConfig(array $config)
    {
        $configFile = '<?php $config = '.var_dump($config);
        file_put_contents(__DIR__.'/config.php', $configFile);
    }

    public function getConfig()
    {
        require __DIR__.'/config.php';

        return $config;
    }
}