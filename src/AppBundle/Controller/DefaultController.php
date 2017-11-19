<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Subscription;
use AppBundle\Form\SubscriptionType;
use AppBundle\Service\SubscriptionData;
use AppBundle\Service\CategoryData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, SubscriptionData $subscriptionData, CategoryData $categoryData)
    {
        $subscription = new Subscription();

        $save = ['label' => 'Subscribe'];
        $form = $this->createForm(SubscriptionType::class, $subscription, compact('categoryData', 'save'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscription = $form->getData();
            $subscriptionData->insert($subscription);

            $this->addFlash('success', 'Thanks for subscribing!');
            return $this->redirectToRoute('homepage');
        }
        
        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
