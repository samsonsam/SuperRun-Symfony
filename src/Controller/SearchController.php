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
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends Controller
{

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $query = $request->request->get('query');
        if ($query != '') {
            $result = $this->getDoctrine()->getRepository('App:Entity\MyUserEntity')->searchForUserByName($query);
            return $this->render('search/search.html.twig', [
                'page_title' => 'Search',
                'search_string' => $query,
                'result' => $result
            ]);
        } else {
            return $this->redirectToRoute('start');
        }

    }

}