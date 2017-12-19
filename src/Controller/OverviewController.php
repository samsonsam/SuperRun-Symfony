<?php
/**
 * Created by PhpStorm.
 * User: samuelerb
 * Date: 17.12.17
 * Time: 16:31
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class OverviewController extends Controller
{

    /**
     * @Route("/run_overview", name="run_overview")
     * @Template
     */
    public function run_overview() {

    }

    /**
     * @Route("/overview", name="overview")
     * @Template
     */
    public function overview() {

    }

}