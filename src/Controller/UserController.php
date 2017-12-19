<?php
/**
 * Created by PhpStorm.
 * MyUserEntity: samuelerb
 * Date: 17.12.17
 * Time: 15:52
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{

    /**
     * @Route("/profile", name="profile")
     * @Template()
     */
    public function profile() {

    }

}