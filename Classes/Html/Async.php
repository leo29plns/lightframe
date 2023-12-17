<?php

namespace lightframe\Html;

class Async
{
    private $registerUsed = false;

    public function unregisterAll() : void
    {
        if (isset($_SESSION['LF_ASYNC']['html'])) {
            unset($_SESSION['LF_ASYNC']['html']);
        }
    }

    public function register(object $component): void
    {
        $this->registerUsed = true;

        $reflection = new \ReflectionClass($component);
        $reflexionIndex = isset($_SESSION['LF_ASYNC']['html']) ? (array_key_last($_SESSION['LF_ASYNC']['html']) + 1) : 0;
        $_SESSION['LF_ASYNC']['html'][$reflexionIndex] = substr($reflection->getName(), strlen('lightframe\\Html'));

        $component->bindId($reflexionIndex);
    }

    public function renderComponent(int $registryIndex, array $params) : string
    {
        if (isset($_SESSION['LF_ASYNC']['html'][$registryIndex])) {
            $componentName = $_SESSION['LF_ASYNC']['html'][$registryIndex];
        } else {
            \LfError::exit('async', 'component_not_found', 404);
        }

        $component = new $componentName();

        if (!($component->bindParams($params))) {
            \LfError::exit('async', 'invalid_component_params', 404);
        }

        return $component->render();
    }

    public function isRegisterUsed() : bool
    {
        return $this->registerUsed;
    }
}