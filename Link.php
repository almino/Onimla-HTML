<?php

namespace Onimla\HTML;

class Link extends Element
{

    use Traits\Href,
        Traits\Target;

    public function __construct($href, $media = FALSE, $type = 'text/css', $rel = 'stylesheet')
    {
        parent::__construct('link');
        $this->rel($rel);
        $this->type($type);
        $this->media($media);
        $this->href($href);

        $this->selfClose(TRUE);
    }

    public function charset($charEncoding = FALSE)
    {
        return $this->attr('charset', $charEncoding);
    }

    public function hrefLang($languageCode = FALSE)
    {
        return $this->attr('hreflang', $languageCode);
    }

    public function media($value = FALSE)
    {
        if ($value !== FALSE) {
            return $this->attr(new Attribute(__FUNCTION__, $value));
        }

        return $this->attr(__FUNCTION__);
    }

    public function rel($value = FALSE)
    {
        return $this->attr('rel', $value);
    }

    public function rev($value = FALSE)
    {
        return $this->attr('rev', $value);
    }

    public function type($MIMEType = FALSE)
    {
        return $this->attr('type', $MIMEType);
    }

}
