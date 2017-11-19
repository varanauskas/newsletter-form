<?php

namespace AppBundle\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {

        return $this->render('security/login.html.twig', [
            'last_username' => $authUtils->getLastUsername(),
            'error' => $authUtils->getLastAuthenticationError()
        ]);
    }
}
