<?php

namespace AppBundle\Entity;

class Category
{
    protected $slug;
    protected $name;

    public function getSlug() { return $this->slug; }
    public function setSlug(string $slug) { $this->slug = $slug; }
    public function getName() { return $this->name; }
    public function setName(string $name) { $this->name = $name; }

    public static function fromArray(array $array)
    {
        $category = new Category();
        $category->setSlug($array[0]);
        $category->setName($array[1]);
        return $category;
    }
    public static function slugFromArray(array $array)
    {
        return $array[0];
    }
    public static function categoryFromSlug(string $slug, array $categories)
    {
        foreach ($categories as $category)
            if ($category->getSlug() == $slug)
                return $category;

        return null;
    }
}