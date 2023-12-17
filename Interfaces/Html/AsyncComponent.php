<?php

namespace lightframe\Interfaces\Html;

interface AsyncComponent extends \Interfaces\Html\Component
{
    public function bindParams(array $params) : bool;
    public function bindId(int $id) : void;
}