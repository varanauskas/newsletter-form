<?php

namespace AppBundle\Service;

use AppBundle\Entity\Subscription;
use AppBundle\Service\CategoryData;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SubscriptionData
{
    private $dataPath;
    private $categories;

    public function __construct(ContainerInterface $container, CategoryData $categoryData)
    {
        $this->dataPath = $container->getParameter('data_path');
        $this->categories = $categoryData->findAll();
    }

    private function generateId()
    {
        return uniqid();
    }

    public function insert(Subscription $subscription)
    {
        $subscription->setId($this->generateId());
        $subscription->setCreated(time());

        $handle = fopen($this->dataPath, 'a');
        fputcsv($handle, $subscription->toArray());
        fclose($handle);
    }

    public function find(string $id)
    {
        $handle = fopen($this->dataPath, 'r');
        while ($array = fgetcsv($handle))
            if (Subscription::idFromArray($array) == $id)
                return Subscription::fromArray($array, $this->categories);
        fclose($handle);
        return null;
    }

    public function findAll()
    {
        $subscriptions = [];

        $handle = fopen($this->dataPath, 'r');
        while ($array = fgetcsv($handle)) {
            $subscription = Subscription::fromArray($array, $this->categories);
            $subscriptions[] = $subscription;
        }
        fclose($handle);
        return $subscriptions;
    }

    public function update(Subscription $subscription)
    {
        $input = fopen($this->dataPath, 'r');
        $output = fopen($this->dataPath + '.new', 'w');

        while ($array = fgetcsv($input)) {
            if ($subscription->getId() == Subscription::idFromArray($array))
                fputcsv($output, $subscription->toArray());
            else fputcsv($output, $array);
        }

        fclose($input);
        fclose($output);

        unlink($this->dataPath);
        rename($this->dataPath + '.new', $this->dataPath);
    }

    public function delete(string $id)
    {
        $input = fopen($this->dataPath, 'r');
        $output = fopen($this->dataPath + '.new', 'w');

        while ($array = fgetcsv($input)) {
            if ($id != Subscription::idFromArray($array))
                fputcsv($output, $array);
        }

        fclose($input);
        fclose($output);

        unlink($this->dataPath);
        rename($this->dataPath + '.new', $this->dataPath);
    }
}
