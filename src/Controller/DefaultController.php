<?php
/**
 * Created by IntelliJ IDEA.
 * MyUserEntity: samuelerb
 * Date: 15.12.17
 * Time: 18:06
 */

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="start")
     * @Template
     */
    public function index()
    {
    }
}