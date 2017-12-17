<?php
/**
 * Created by IntelliJ IDEA.
 * User: samuelerb
 * Date: 15.12.17
 * Time: 18:06
 */

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Default_Controller extends Controller
{
    /**
     * @Route("/", name="start")
     * @Template
     */
    public function index()
    {
    }
}