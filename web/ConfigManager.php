<?php

class ConfigManager
{
    /**
     * Location to the config file.
     */
    protected $configLocation;

    public function __construct($config_location = null)
    {
        if(!$config_location) {
            $config_location = __DIR__.'/config.php';
        }

        $this->configLocation = $config_location;
    }

    public function setConfig(array $config)
    {
        $configFile = '<?php $config = '.var_export($config, true).';';
        file_put_contents($this->configLocation, $configFile);
    }

    public function getConfig()
    {
        require $this->configLocation;

        return $config;
    }
}