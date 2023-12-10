<?php

namespace lightframe\HtmlBuild;

class Template
{
    private $templatePath;
    private $template;

    public function __construct(string $templatePath, array $template = null)
    {
        $this->templatePath = 'html' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $templatePath;
        if ($template) {
            $this->template = $template;
        }
    }

    public function render() : string
    {
        if ($this->template) {
            $template = $this->template;
        }

        ob_start();
        require($this->templatePath);
        $html = ob_get_clean();

        $translation = \Parser::getTranslationFile($_SESSION['LF_LOCALE'], $this->templatePath, 'templates', 'html' . DIRECTORY_SEPARATOR . 'templates');

        $parser = new \Parser($html);
        $parser->bind($translation[0]);
        $html = $parser->parse();

        $fallBackParser = new \Parser($html);
        $fallBackParser->bind($translation[1]);
        $html = $fallBackParser->parse();

        return $html;
    }
}