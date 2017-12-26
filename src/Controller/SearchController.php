<?php
/**
 * Created by PhpStorm.
 * User: samuelerb
 * Date: 26.12.17
 * Time: 14:14
 */

namespace App\Controller;

use App\Entity\MyUserEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends Controller
{

    /**
     * @Route("/search", name="search")
     * @Template
     */
    public function search(Request $request) {
        $query = $request->request->get('query');
        $this->denyAccessUnlessGranted('ROLE_USER');

        $result = $this->getDoctrine()->getRepository('App:Entity\MyUserEntity')->searchForUserByName($query);
        return [
            'page_title' => 'Search',
            'search_string' => $query,
            'result' => $result
        ];
    }

}