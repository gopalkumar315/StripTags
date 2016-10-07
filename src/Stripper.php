<?php
namespace Mimrahe\StripTags;
/**
 * strip_tags() replacement class
 *
 * @package     mimrahe/striptags
 * @author      mohsen ranjbar <mimrahe@gmail.com>
 */

class Stripper
{
    /**
     * subject to be stripped
     * @var array|string
     */
    protected $subject = '';

    /**
     * allowed tags string
     * @var string
     */
    protected $allowedTags = '';

    /**
     * Stripper constructor.
     * @param array|string $subject
     */
    public function __construct($subject = '')
    {
        if (!empty($subject)) {
            return $this->on($subject);
        }
    }

    /**
     * defines subject to be stripped
     * @param array|string $subject
     * @return $this
     */
    public function on($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * defines not allowed tags
     * @param array $notAllowedTags
     * @return $this
     */
    public function only(array $notAllowedTags)
    {
        $this->allowedTags = $this->tags();
        $this->allowedTags = array_diff($this->allowedTags, $notAllowedTags);
        return $this;
    }

    /**
     * defines allowed tags
     * @param array $allowedTags
     * @return $this
     */
    public function except(array $allowedTags)
    {
        $this->allowedTags = $allowedTags;
        return $this;
    }

    /**
     * stripes $subject with $allowedTags
     * @return string
     */
    public function strip()
    {
        $allowedTags = $this->make();

        if(is_array($this->subject)){
            return $this->stripArray($allowedTags, $this->subject);
        }

        return strip_tags($this->subject, $allowedTags);
    }

    /**
     * makes strip_tags() allowed tags string
     * @return string
     */
    protected function make()
    {
        $tags = implode('><', $this->allowedTags);

        return '<' . $tags . '>';
    }

    /**
     * strip array subjects
     * @param string $allowedTags
     * @return array
     */
    protected function stripArray($allowedTags, $subjectArray)
    {
        $stripped = [];
        foreach ($subjectArray as $key => $subject) {
            if(is_array($subject)){
                $stripped[$key] = $this->stripArray($allowedTags, $subject);
                continue;
            }
            $stripped[$key] = strip_tags($subject, $allowedTags);
        }
        return $stripped;
    }

    /**
     * returns html elements/tags list in an array
     * @return array
     */
    protected function tags()
    {
        return [
            // basic
            '!DOCTYPE', 'html', 'title', 'body',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'p', 'br', 'hr',
            // formatting
            'acronym', 'abbr', 'address', 'b', 'bdi', 'bdo', 'big',
            'blockquote', 'center', 'cite', 'code', 'del', 'dfn', 'em',
            'font', 'i', 'ins', 'kbd', 'mark', 'meter', 'pre', 'progress',
            'q', 'rp', 'rt', 'ruby', 's', 'samp', 'small', 'strike', 'strong',
            'sub', 'sup', 'time', 'tt', 'u', 'var', 'wbr',
            // forms and input
            'form', 'input', 'textarea', 'button', 'select', 'optgroup', 'option',
            'label', 'fieldset', 'legend', 'datalist', 'keygen', 'output',
            // frames
            'frame', 'frameset', 'noframes', 'iframe',
            // images
            'img', 'map', 'area', 'canvas', 'figcaption', 'figure',
            // audio and video
            'audio', 'source', 'track', 'video',
            // links
            'a', 'link', 'nav',
            // lists
            'ul', 'ol', 'li', 'dir', 'dl', 'dt', 'dd', 'menu', 'menuitem',
            // tables
            'table', 'caption', 'th', 'tr', 'td', 'thead', 'tbody', 'tfoot', 'col', 'colgroup',
            // styles and semantics
            'style', 'div', 'span', 'header', 'footer', 'main', 'section', 'article',
            'aside', 'details', 'dialog', 'summary',
            // meta info
            'head', 'meta', 'base', 'basefont',
            // programming
            'script', 'noscript', 'applet', 'embed', 'object', 'param'
        ];
    }
}