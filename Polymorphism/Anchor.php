<?php

namespace Onimla\HTML\Polymorphism;

interface Anchor
{

    public function href($url = FALSE);

    public function title($text = FALSE);

    public function download();

    public function setDownload();

    public function unsetDownload();
}
