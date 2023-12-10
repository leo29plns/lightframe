<?php

namespace lightframe;

class PhpToJs
{
    private $datasetName;
    private $data;

    public function __construct(string $datasetName, string|array $data)
    {
        $this->datasetName = $datasetName;
        $this->data = $data;
    }

    public function render() : string
    {
        $jsonData = json_encode($this->data);

        $cleanDatasetName = htmlspecialchars($this->datasetName);
        $cleanJsonData = htmlspecialchars($jsonData);

        return "<div class=\"php-to-js\" data-$cleanDatasetName=\"$cleanJsonData\"></div>";
    }
}