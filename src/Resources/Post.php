<?php

namespace Tradzero\WPREST\Resources;

use JsonSerializable;
use Datetime;
use DateTimeZone;

class Post implements JsonSerializable
{
    const STATUS_POST_PUBLISH = 'publish';
    const STATUS_POST_FUTURE  = 'future';
    const STATUS_POST_DRAFT   = 'draft';
    const STATUS_POST_PENDING = 'pending';
    const STATUS_POST_PRIVATE = 'private';

    const STATUS_COMMENT_OPEN = 'open';
    const STATUS_COMMENT_CLOSE = 'closed';

    protected $id;

    protected $date;

    protected $date_gmt;
    /**
     * 文章索引名
     *
     * @var [string]
     */
    protected $slug;

    protected $status;

    /**
     * 文章是否被密码保护
     *
     * @var [string] 文章的密码 留空为不加密
     */
    protected $password;

    protected $title;

    protected $content;

    protected $excerpt;

    /**
     * 评论是否开放
     *
     * @var [enum] One of: open, closed
     */
    protected $comment_status;

    protected $format = 'standard';

    protected $meta;

    /**
     * 是否置顶文章
     *
     * @var [boolean]
     */
    protected $sticky;

    protected $categories;

    protected $tags;

    public function __construct()
    {
        $this->initData();
    }
    
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
        $this->updateSlug();
    }

    public function setCategories(array $categories)
    {
        foreach ($categories as $category) {
            if ($category->getId()) {
                array_push($this->categories, $category->getId());
            }
        }
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    protected function initData()
    {
        $this->initDate();
        $this->status         = self::STATUS_POST_PUBLISH;
        $this->password       = '';
        $this->title          = '';
        $this->content        = '';
        $this->excerpt        = '';
        $this->comment_status = self::STATUS_COMMENT_OPEN;
        $this->meta           = [];
        $this->sticky         = false;
        $this->categories     = [];
        $this->tags           = [];
    }

    protected function updateSlug()
    {
        $this->slug = urlencode('blog' . $this->title);
    }

    protected function initDate()
    {
        $datetime        = new Datetime('NOW');
        $UTCTimezone     = new DateTimeZone('UTC');
        $wordpressFormat = 'Y-m-d\TH:i:s';
        $this->date      = $datetime->format($wordpressFormat);
        $this->date_gmt  = $datetime->setTimezone($UTCTimezone)
                                   ->format($wordpressFormat);
    }
}