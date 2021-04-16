<?php


namespace app\components;


use Exception;

class Template
{
    private const MAIN_TEMPLATE = 'main';
    private const DEFAULT_TEMPLATE_PARAM_KEY= 'template';
    private string $viewsDir;
    private string $templatesDir;
    private ?array $params = null;
    private ?string $content = null;

    public function __construct(string $viewsDir, string $templatesDir)
    {
        $this->viewsDir = $viewsDir;
        $this->templatesDir = $templatesDir;
    }

    public function __get($name)
    {
        if(array_key_exists($name, $this->params)){
            return $this->params[$name];
        }
        return 'Params does not exist';
    }

    /**
     * @param string $view
     * @param array|null $params
     * @return string
     * @throws Exception
     */
    public function render(string $view, ?array $params = null): string
    {
        $template = self::MAIN_TEMPLATE;

        if($this->params = $this->checkParamsExist($params)){
            $template = $this->checkParamKeyExist(self::DEFAULT_TEMPLATE_PARAM_KEY)
                ? $this->params[self::DEFAULT_TEMPLATE_PARAM_KEY]
                : self::MAIN_TEMPLATE;
        }

        $this->content = $this->findView($view);

        return $this->findView($template, true);
    }

    private function findView(string $view, bool $isTemplate = false): string
    {
        $filePath =  $isTemplate ? $this->templatesDir : $this->viewsDir;
        $filePath .= "/{$view}.php";
        if(!file_exists($filePath) || !is_file($filePath)){
            throw new Exception("File with route '{$filePath}' does not exist");
        }

        ob_start();
        require_once $filePath;
        return ob_get_clean();
    }

    private function checkParamsExist(?array $params): ?array
    {
        return !empty($params) ? $params : null;
    }

    private function checkParamKeyExist(string $key): bool
    {
        return array_key_exists($key, $this->params);
    }
}