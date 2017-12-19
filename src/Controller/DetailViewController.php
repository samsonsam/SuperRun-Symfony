<?php
/**
 * Created by PhpStorm.
 * User: samuelerb
 * Date: 17.12.17
 * Time: 16:36
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DetailViewController extends Controller
{
    /**
     * @Route("/detail_view", name="detail_view")
     * @Template
     */
    public function detailview() {

    }

}