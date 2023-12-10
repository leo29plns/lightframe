<?php

namespace lightframe\HtmlBuild;

class Parser
{
    private $html;
    private $placeholders = [];

    public function __construct(string $html)
    {
        $this->html = $html;
    }

    public function bind(?array $placeholders) : void
    {
        if ($placeholders) {
            $this->placeholders = array_merge($this->placeholders, $placeholders);
        }
    }

    private function parseLocale() : void
    {
        foreach ($this->placeholders as $key => $value) {
            $pattern = "/{{\s*$key\s*}}/";
            $this->html = preg_replace($pattern, $value, $this->html);
        }
    }

    private function parseLinks() : void
    {
        $pattern = '/\{: (.*?) :\}/';
        $this->html = preg_replace_callback($pattern, function($matches) {
            return $_ENV['LF_WEBROOT'] . '/' . trim($matches[1], '/');
        }, $this->html);
    }

    public function parse() : string
    {
        $this->parseLocale();
        $this->parseLinks();

        return $this->html;
    }


    public static function getTranslationFile(string $locale, string $fileToTanslate, string $translationsSubDirectory, string $fileDirectory) : array
    {
        $translationsSubDirectory ? (DIRECTORY_SEPARATOR . $translationsSubDirectory) : '';

        $translationsDirectory = 'locales' . DIRECTORY_SEPARATOR  . $locale . DIRECTORY_SEPARATOR . $translationsSubDirectory;
        $translationFileInfo = pathinfo($fileToTanslate);
        $translationFile = substr($translationFileInfo['dirname'], strlen($fileDirectory)) . DIRECTORY_SEPARATOR . $translationFileInfo['filename'] . '.yml';
        if (file_exists($translationsDirectory . DIRECTORY_SEPARATOR . $translationFile)) {
            $translationFile = file_get_contents($translationsDirectory . DIRECTORY_SEPARATOR . $translationFile);
            $selectedLocale = \Spyc\Spyc::YAMLLoad($translationFile);
        } else {
            $selectedLocale = null;
        }

        $translationsDirectory = 'locales' . DIRECTORY_SEPARATOR  . \LF_DEFAULT_LOCALE . DIRECTORY_SEPARATOR . $translationsSubDirectory;
        $translationFileInfo = pathinfo($fileToTanslate);
        $translationFile = substr($translationFileInfo['dirname'], strlen($fileDirectory)) . DIRECTORY_SEPARATOR . $translationFileInfo['filename'] . '.yml';
        if (file_exists($translationsDirectory . DIRECTORY_SEPARATOR . $translationFile)) {
            $translationFile = file_get_contents($translationsDirectory . DIRECTORY_SEPARATOR . $translationFile);
            $fallBackLocale = \Spyc\Spyc::YAMLLoad($translationFile);
        } else {
            $fallBackLocale = null;
        }

        return [$selectedLocale, $fallBackLocale];
    }
}