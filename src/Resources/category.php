<?php

namespace Tradzero\WPREST\Resources;

use JsonSerializable;

class Category implements JsonSerializable
{
    protected $id;

    protected $description;

    protected $name;

    protected $slug;

    protected $meta;

    public function setId($id)
    {
        $this->id = $id;    
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public static function build($jsonResult)
    {
        $category = new Category();

        $category->setId($jsonResult->id);

        $category->setDescription($jsonResult->description);

        $category->setName($jsonResult->name);

        $category->setSlug($jsonResult->slug);

        return $category;
    }
}