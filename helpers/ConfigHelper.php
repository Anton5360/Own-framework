<?php


namespace app\helpers;


use app\components\App;
use Exception;

class ConfigHelper
{
    static private ?array $config = null;

    static public function initConfig(array $config): void
    {
        if(self::$config){
           throw new Exception('Config has already been initiated');
        }

        self::$config = $config;
    }

    static public function getConfigValue(string $keys, string $separator = '.'): string
    {
        $configValue = self::$config;

        if(!$keys){
            return '';
        }

        $explodeKeys = explode($separator, $keys);
        foreach($explodeKeys as $key){
            $configValue = $configValue[$key];
        }

        if(!$configValue){
            throw new Exception("Config value {$keys} does not exist");
        }

        return $configValue;
    }
}