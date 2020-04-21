<?php
namespace app\templates\excel;

abstract class ExcelTemplate
{
    public function getPath()
    {
        return $this->getDirectory() . $this->getFilename();
    }

    protected function getParams() {
        return [];
    }

    public function isGenerated() {
        return is_file($this->getPath());
    }

    public abstract function generate();

    public abstract function getDirectory();

    public abstract function getFilename();
}