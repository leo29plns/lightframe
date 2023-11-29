<?php

namespace lightframe\htmlBuild;

class Template
{
    private $templateHTML;

    public function __construct(string $templatePath)
    {
        $this->templateHTML = file_get_contents($templatePath);
    }

    public function bindParam(string $paramName, string $paramValue) : void
    {
        
    }
}