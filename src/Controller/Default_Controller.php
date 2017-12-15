<?php
/**
 * Created by IntelliJ IDEA.
 * User: samuelerb
 * Date: 15.12.17
 * Time: 18:06
 */

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class Default_Controller extends Controller
{
    /**
     * @Route("/", name="start")
     * @Template
     * @param $name
     */
    public function index($name)
    {
    }
}