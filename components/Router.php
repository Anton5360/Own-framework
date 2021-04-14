<?php


namespace app\components;


use app\helpers\ConfigHelper;
use Exception;
use ReflectionMethod;

class Router
{
    private ?Dispatcher $dispatcher = null;
    private string $controllerName;
    private string $actionName;

    public function __construct(string $url)
    {
        $this->initDispatcher($url);
        $this->prepareControllerName();
        $this->prepareActionName();
        $this->callControllerMethod($this->controllerName, $this->actionName);
    }


    private function callControllerMethod(string $controllerName, string $action): void
    {
        if(!class_exists($controllerName) || !method_exists($controllerName, $action)){
            throw new Exception('Undefined controller call');
        }
        $controller = new $controllerName();

        $processedParams = $this->prepareParams($controller, $action, $this->dispatcher->getParams());

        $content = $controller->{$action}(...$processedParams);

    }


    private function initDispatcher(string $url): void
    {
        $this->dispatcher = new Dispatcher($url);
//        return $this;
    }

    /**
     * @throws Exception
     */
    private function prepareControllerName(): void
    {
        $controller = $this->prepareSentence($this->dispatcher->getController());
        $namespace = ConfigHelper::getConfigValue('components.router.controllersNamespace');
        $this->controllerName = "{$namespace}{$controller}Controller";
    }

    private function prepareActionName(): void
    {
        $action = $this->prepareSentence($this->dispatcher->getAction());
        $this->actionName = "action{$action}";
    }

    private function prepareParams(object $controller, string $action, array $params): array
    {
        $reflectionAction = new ReflectionMethod($controller, $action);
        $numberOfRequiredParams = $reflectionAction->getNumberOfRequiredParameters();
        $actionParams = $reflectionAction->getParameters();
        $processedParams = [];

        foreach($actionParams as $param){
//            var_dump(array_key_exists($param->name, $params), $params, $param->name);exit;
            if(array_key_exists($param->name, $params)){
                $processedParams[] = $params[$param->name];
            } elseif(count($processedParams) >= $numberOfRequiredParams)  {
                continue;
            } else {
                break;
            }
        }

        return $processedParams;
    }

    private function prepareSentence(string $sentence, string $symbol = '-'): string
    {
        $prepairedSentence = '';

        if(strpos($sentence, $symbol) !== false){
            $words = explode($symbol, $sentence);
            foreach($words as $word){
                $prepairedSentence .= $this->prepareWord($word);
            }
        } else {
            $prepairedSentence .= $this->prepareWord($sentence);
        }

        return $prepairedSentence;
    }

    private function prepareWord(string $word): string
    {
        return ucfirst(strtolower($word));
    }

}