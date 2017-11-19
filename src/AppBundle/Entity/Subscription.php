<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;

class Subscription
{
    protected $id;

    /**
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;

    /**
     * @Assert\Count(min = 1)
     */
    protected $categories;

    protected $created;
    protected $deleted;

    public function getId() { return $this->id; }
    public function setId(string $id) { $this->id = $id; }
    public function getName() { return $this->name; }
    public function setName(string $name) { $this->name = $name; }
    public function getEmail() { return $this->email; }
    public function setEmail(string $email) { $this->email = $email; }
    public function getCategories() { return $this->categories; }
    public function setCategories(array $categories) { $this->categories = $categories; }
    public function getCreated() { return $this->created; }
    public function setCreated(int $created) { $this->created = $created; }

    public function toArray() { return array_merge([$this->id, $this->name, $this->email, $this->created], array_map(function($category) { return $category->getSlug(); }, $this->categories)); }
    public static function fromArray(array $array, array $categories)
    {
        $subscription = new Subscription();
        $subscription->setId($array[0]);
        $subscription->setName($array[1]);
        $subscription->setEmail($array[2]);
        $subscription->setCreated($array[3]);
        $subscription_categories = [];
        for ($i = 5; $i < count($array); $i++)
            $subscription_categories[] = Category::categoryFromSlug($array[$i], $categories);
        $subscription->setCategories($subscription_categories);
        return $subscription;
    }
    public static function idFromArray(array $array)
    {
        return $array[0];
    }
}