<?php

namespace AppBundle\Service;

use AppBundle\Entity\Category;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CategoryData
{
    private $dataPath;

    public function __construct(ContainerInterface $container)
    {
        $this->dataPath = $container->getParameter('categories_path');
    }

    public function findAll()
    {
        $categories = [];

        $handle = fopen($this->dataPath, 'r');
        while ($array = fgetcsv($handle)) {
            $category = Category::fromArray($array);
            $categories[] = $category;
        }
        fclose($handle);

        return $categories;
    }
}
