<?php

namespace Tahir\View;

interface ViewInterface
{
    public function getOutput();

    public function __toString();
}