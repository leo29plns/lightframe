<?php

namespace lightframe;

class LfError
{
    public static function exit(string $category, string $message, int $httpCode = 500) : void
    {
        http_response_code($httpCode);

        $template['LF_ERRCODE'] = $httpCode;
        $template['LF_TITLE'] = $httpCode;
        $template['LF_ERRMESSAGE'] = $message;

        ob_start();
        require('html' . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR . 'default.php');
        $html = ob_get_clean();

        $localePath = 'locales' . DIRECTORY_SEPARATOR . $_SESSION['LF_LOCALE'] . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR;
        $defaultLocalePath = 'locales' . DIRECTORY_SEPARATOR . \LF_DEFAULT_LOCALE . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR;
        
        $fileCommon = 'common.yml';
        $fileCategory = $category . '.yml';
        
        if (file_exists($localePath . $fileCommon)) {
            $translation[0] = \Spyc\Spyc::YAMLLoad(file_get_contents($localePath . $fileCommon));

            $parser = new \Parser($html);
            $parser->bind($translation[0]);
            $html = $parser->parse();
        }
        
        if (file_exists($defaultLocalePath . $fileCommon)) {
            $translation[1] = \Spyc\Spyc::YAMLLoad(file_get_contents($defaultLocalePath . $fileCommon));

            $parser = new \Parser($html);
            $parser->bind($translation[1]);
            $html = $parser->parse();
        }
        
        if (file_exists($localePath . $fileCategory)) {
            $translation[2] = \Spyc\Spyc::YAMLLoad(file_get_contents($localePath . $fileCategory));

            $parser = new \Parser($html);
            $parser->bind($translation[2]);
            $html = $parser->parse();
        }
        
        if (file_exists($defaultLocalePath . $fileCategory)) {
            $translation[3] = \Spyc\Spyc::YAMLLoad(file_get_contents($defaultLocalePath . $fileCategory));

            $parser = new \Parser($html);
            $parser->bind($translation[3]);
            $html = $parser->parse();
        }

        echo $html;
        exit();
    }
}