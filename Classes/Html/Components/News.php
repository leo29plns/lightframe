<?php

namespace lightframe\Html\Components;

class News implements \Interfaces\Html\AsyncComponent
{
    private $asyncId;
    private $params;

    public function render() : string
    {
        return '<div class="news" data-async_html="' . $this->asyncId . '">' . time() . '<br>' . json_encode($this->params) . '</div>';
    }

    public function bindParams(array $params = []) : bool
    {
        $this->params = $params;
        return true;
    }

    public function bindId(int $id) : void
    {
        $this->asyncId = $id;
    }
}