<?php

namespace lightframe;

class View
{
    private $viewPath;
    private $pageTitle;
    private $pageDescription;

    private $cssFiles = [];
    private $jsFiles = [];
    private $phpToJsHtml;

    private $asyncHtml;

    public function __construct(string $viewPath)
    {
        $this->viewPath = 'html' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $viewPath;

        $this->asyncHtml = new \Html\Async;
        $this->asyncHtml->unregisterAll();
    }

    public function setTitle(string $title) : void
    {
        $this->pageTitle = $title;
    }

    public function setDescription(string $description) : void
    {
        $this->pageDescription = $description;
    }

    private function addCss(string $cssFile) : void
    {
        $this->cssFiles[] = $cssFile;
    }

    private function addJs(string $jsFile) : void
    {
        $this->jsFiles[] = $jsFile;
    }

    public function phpToJs(string $datasetName, string|array $data) : void
    {
        $phpToJs = new PhpToJs($datasetName, $data);
        $html = $phpToJs->render();

        $this->phpToJsHtml .= $html;
    }

    public function render() : string
    {
        ob_start();
        require($this->viewPath);
        $html = ob_get_clean();

        if ($this->asyncHtml->isRegisterUsed()) {
            $this->phpToJs('lf_token', $_SESSION['LF_TOKEN']);
        }

        if ($this->pageTitle) {
            $template['head']['LF_TITLE'] = $this->pageTitle;
        }

        if ($this->pageDescription) {
            $template['head']['LF_DESCRIPTION'] = $this->pageDescription;
        }

        if ($this->phpToJsHtml) {
            $template['footer']['LF_PHPTOJS'] = $this->phpToJsHtml;
        }

        $template['head']['LF_CSSFILES'] = $this->cssFiles;
        $template['footer']['LF_JSFILES'] = $this->jsFiles;

        $translation = \Parser::getTranslationFile($_SESSION['LF_LOCALE'], $this->viewPath, 'views', 'html' . DIRECTORY_SEPARATOR . 'views');

        $headHtmlTemplate = new \Template('lf' . DIRECTORY_SEPARATOR . 'head.php', $template['head']);
        $bodyHtmlTemplate = new \Template('lf' . DIRECTORY_SEPARATOR . 'footer.php', $template['footer']);

        if (isset($translation[0]['body'])) {
            $parser = new \Parser($html);
            $parser->bind($translation[0]['body']);
            $html = $parser->parse();
        }

        if (isset($translation[1]['body'])) {
            $fallBackparser = new \Parser($html);
            $fallBackparser->bind($translation[1]['body']);
            $html = $fallBackparser->parse();
        }

        $headHtml = $headHtmlTemplate->render();

        if (isset($translation[0]['head'])) {
            $headParser = new \Parser($headHtml);
            $headParser->bind($translation[0]['head']);
            $headHtml = $headParser->parse();
        }

        if (isset($translation[1]['head'])) {
            $fallBackHeadParser = new \Parser($headHtml);
            $fallBackHeadParser->bind($translation[1]['head']);
            $headHtml = $fallBackHeadParser->parse();
        }

        $bodyHtml = $bodyHtmlTemplate->render();

        return $headHtml . $html . $bodyHtml;
    }
}