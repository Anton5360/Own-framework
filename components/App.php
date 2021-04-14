<?php


namespace app\components;


use app\helpers\ConfigHelper;
use Exception;

class App
{
    private array $config;

    static public ?App $app = null;
    
    private function __construct(array $config)
    {
        if(!$config){
            throw new Exception('Config are required');
        }
        $this->config = $config;
        $this->run();
    }

    private function __clone(){}
    
    
    static public function init(array $config): void
    {
        if(self::$app){
            throw new Exception('App has already been initiated ');
        }
        
        self::$app = new self($config);
    }
    
    static public function get(): App
    {
        if(!self::$app){
            throw new Exception('App has not been initiated yet');
        }
        
        return self::$app;
    }

    private function run(): void
    {

        $this
            ->initConfigHelper()
            ->initRouter();
    }
    
    private function initRouter(): self
    {
        (new Router($_SERVER['REQUEST_URI']));
        return $this;
    }

    private function initConfigHelper(): self
    {
        ConfigHelper::initConfig($this->config);
        return $this;
    }
}