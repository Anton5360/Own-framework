<?php


namespace app\components;


class Dispatcher
{
    private const DEFAULT_CONTROLLER_NAME = 'index';
    private const DEFAULT_ACTION_NAME = 'index';

    private string $url;
    private ?string $controller = null;
    private ?string $action = null;
    private array $params = [];

    public function __construct(string $url)
    {
        $this->url = $this->getPrepareUrl($url);
        $this->splitRequest();
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    private function getPrepareUrl(string $url): string
    {
        $isHasGetParams = strpos($url, '?');

        if($isHasGetParams){
            return $this->prepareBothParts($url, $isHasGetParams);
        }

        return trim($url, "/ \t\n\r\0\x0B");
    }

    private function prepareBothParts(string $url, int $questionMarkPosition): string
    {
        $urlMainPart = $this->getMainUrlPart($url, $questionMarkPosition);
        $getParamsPart = $this->getGetRequestUrlPart($url, $questionMarkPosition);
        $parts = [$urlMainPart, '?' , $getParamsPart];
        $fullUrl = '';

        foreach($parts as $part){
            $fullUrl .= trim($part, "/ \t\n\r\0\x0B");
        }

        return $fullUrl;
    }

    private function getMainUrlPart(string $url, int $questionMarkPosition): string
    {
        return !$questionMarkPosition ? $url : substr($url, 0,$questionMarkPosition);
    }

    private function getGetRequestUrlPart(string $url, int $questionMarkPosition): string
    {
        return !$questionMarkPosition ? $url : substr($url, ++$questionMarkPosition);
    }

    private function splitRequest(): void
    {
        $explodeUrl = $this->url ?
            explode('/', $this->getMainUrlPart($this->url, strpos($this->url, '?')))
            :
            [];


        $this->controller = !empty($explodeUrl) ? array_shift($explodeUrl) : self::DEFAULT_CONTROLLER_NAME;
        $this->action = !empty($explodeUrl) ? array_shift($explodeUrl) : self::DEFAULT_ACTION_NAME;


        if(!empty($explodeUrl)){
            $this->prepareParams($explodeUrl);
        }
    }


    private function prepareParams(array $params): void
    {
        $keys = [];
        $values = [];
        foreach($params as $key => $value){
            if(!$value){
                continue;
            }
            if($key % 2 === 0){
                $keys[] = $value;
                continue;
            }
            $values[] = $value;
        }

        if(count($keys) > count($values)){
            $values[] = null;
        }

        $this->params = array_merge(array_combine($keys, $values), $_GET);
    }
}