<?php
/**
 * Created by PhpStorm.
 * User: samuelerb
 * Date: 10.01.18
 * Time: 13:45
 */

namespace App\Controller;

use App\Entity\MyUserEntity;
use App\Entity\RunEntity;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\{
    DateTimeType, DateType, IntegerType, SubmitType, TimeType
};
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Translation\TranslatorInterface;



class OverviewController extends Controller
{
    /**
     * @Route("/overview", name="overview")
     * @Template
     */
    public function overview(Request $request, ObjectManager $manager, UserInterface $loggedin_user = null, TranslatorInterface $translator)
    {
        $users = $this->getDoctrine()->getRepository('App:Entity\MyUserEntity')->findAll();

        $data = [];
        foreach ($users as $user) {
            $data[] = $user->getOverviewData();
        }

        $response['page_title'] = 'Übersicht';
        $response['data'] = $data;
        return $response;
    }

}

/**
 * vars needed:
id
name
days
distance
 */