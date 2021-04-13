<?php


namespace app\components;


use Exception;

class App
{
    private array $config;
    static public ?App $app = null;

    private function __construct(array $config)
    {
        $this->config = $config;
        $this->run();
    }


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

    }


}