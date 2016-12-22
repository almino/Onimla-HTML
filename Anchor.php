<?php

namespace Onimla\HTML;

class Anchor extends Element implements Polymorphism\Anchor
{

    use Traits\Href;
    
    

    public function __construct($text = FALSE, $href = FALSE, $title = FALSE)
    {
        parent::__construct('a');
        $this->href($href);
        $this->title($title);
        $this->selfClose(FALSE);
        $this->text($text);
    }

    public function download()
    {
        return $this->setDownload();
    }

    public function isDownload()
    {
        return $this->hasAttribute(Constant::DOWNLOAD);
    }

    public function isNotDownload()
    {
        return !$this->isDownload();
    }

    public function setDownload()
    {
        return $this->attr(new Attribute(Constant::DOWNLOAD));
    }

    public function unsetDownload()
    {
        return $this->removeAttr(Constant::DOWNLOAD);
    }

}
