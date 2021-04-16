<?php


namespace app\components;


use app\helpers\ConfigHelper;
use Exception;

class App
{
    private array $config;

    static public ?App $app = null;
    public ?Database $db = null;
    public ?Template $template = null;
    
    private function __construct(array $config)
    {
        if(!$config){
            throw new Exception('Config are required');
        }
        $this->config = $config;
    }

    private function __clone(){}
    
    
    static public function init(array $config): void
    {
        if(self::$app){
            throw new Exception('App has already been initiated ');
        }

        self::$app = new self($config);
        self::$app->run();
    }
    
    static public function get(): App
    {
        if(!self::$app){
            throw new Exception('App has not been initiated yet');
        }
        
        return self::$app;
    }

    public function db(): Database
    {
        return $this->db;
    }

    public function template(): Template
    {
        return $this->template;
    }


    private function run(): void
    {
//        var_dump(App::$app);exit;
        $this
            ->initConfigHelper()
            ->initDB()
            ->initTemplate()
            ->initRouter();
    }
    
    private function initRouter(): self
    {
        (new Router($_SERVER['REQUEST_URI']));
        return $this;
    }

    private function initDB(): self
    {
        $host = ConfigHelper::getConfigValue('components.db.host');
        $name = ConfigHelper::getConfigValue('components.db.name');
        $user = ConfigHelper::getConfigValue('components.db.user');
        $password = ConfigHelper::getConfigValue('components.db.password');

        $this->db = new Database($host, $name, $user, $password);
        if(!$this->db){
            throw new Exception('Database connection has not been established');
        }
        return $this;
    }

    private function initTemplate(): self
    {
        $viewsDir = ConfigHelper::getConfigValue('components.views.baseDir');
        $templatesDir = ConfigHelper::getConfigValue('components.template.baseDir');
        $this->template = new Template($viewsDir, $templatesDir);
        if(!$this->template){
            throw new Exception('Template component crashed');
        }
        return $this;
    }

    private function initConfigHelper(): self
    {
        ConfigHelper::initConfig($this->config);
        return $this;
    }
}