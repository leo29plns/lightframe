<?php

namespace lightframe\htmlBuild;

class Parser
{
    private $html;
    private $placeholders;

    public function __construct(string $html)
    {
        $this->html = $html;
    }

    public function bind(array $placeholders) : void
    {
        $this->placeholders = array_merge($this->placeholders, $placeholders);
    }

    public function parse() : string
    {
        foreach ($this->placeholders as $key => $value) {
            $pattern = "/{{\s*$key\s*}}/";
            $this->html = preg_replace($pattern, $value, $this->html);
        }

        return $this->html;
    }
}