<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Subscription;
use AppBundle\Service\SubscriptionData;
use AppBundle\Service\CategoryData;
use AppBundle\Form\SubscriptionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function indexAction(Request $request, SubscriptionData $subscriptionData)
    {
        $subscriptions = $subscriptionData->findAll();

        $order = $request->query->get('order') == 'descending' ? -1 : 1;
        $sortBy = $request->query->get('sortBy');
        switch ($sortBy) {
            case 'name':
                usort($subscriptions, function ($a, $b) use ($order) {
                    return $order * strcmp($a->getName(), $b->getName());
                });
                break;
            case 'email':
                usort($subscriptions, function ($a, $b) use ($order) {
                    return $order * strcmp($a->getEmail(), $b->getEmail());
                });
                break;
            case 'created':
                usort($subscriptions, function($a, $b) use ($order) {
                    return $order * ($a->getCreated() - $b->getCreated());
                });
                break;
        }

        return $this->render('admin/index.html.twig', compact('subscriptions', 'order', 'sortBy'));
    }

    /**
     * @Route("/admin/{id}/delete", name="admin_delete")
     */
    public function deleteAction(string $id, SubscriptionData $subscriptionData)
    {
        $subscriptionData->delete($id);
        $this->addFlash('success', 'The subscription was deleted from the file');
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin/{id}/edit", name="admin_edit")
     */
    public function editAction(Request $request, string $id, SubscriptionData $subscriptionData, CategoryData $categoryData)
    {
        $subscription = $subscriptionData->find($id);
        
        $save = ['label' => 'Save'];
        $form = $this->createForm(SubscriptionType::class, $subscription, compact('categoryData', 'save'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscription = $form->getData();
            $subscriptionData->update($subscription);
            $this->addFlash('success', 'The subscription was edited  successfully');
        }
        
        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
