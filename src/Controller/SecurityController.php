<?php
/**
 * Created by PhpStorm.
 * MyUserEntity: samuelerb
 * Date: 17.12.17
 * Time: 23:46
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends Controller {
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();
        if ($error) {
            $result['error'] = $error->getMessage();
        }

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();
        $result['last_username'] = $lastUsername;


        return $this->render('login/login.html.twig', $result);
    }

    /**
     * @Route("/logout", name="logout")
     * @Template
     */
    public function logout(Request $request, AuthenticationUtils $authUtils) {
        return $this->render('login/login.html.twig', array(
            'page_title' => 'Index'
        ));
    }
}