<?php
/**
 * Created by PhpStorm.
 * MyUserEntity: samuelerb
 * Date: 17.12.17
 * Time: 15:52
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

class UserController extends Controller
{
    private $user;


    /**
     * @Route("/profile/show_{id}", requirements={"id" = "\d+"}, name="profile")
     * @Template
     */
    public function profile($id, Request $request, ObjectManager $manager, UserInterface $loggedin_user = null, TranslatorInterface $translator)
    {
        $isOwner = null;
        if ($loggedin_user == null) {
            // create temporary user for profile owner check
            $loggedin_user = new MyUserEntity();
            $loggedin_user->setId(-1);
        } else {
            $loggedin_user = $this->getDoctrine()->getRepository('App:Entity\MyUserEntity')->loadUserByUsername($loggedin_user->getUsername());
        }
        $owner = $this->getDoctrine()->getRepository('App:Entity\MyUserEntity')->loadUserByID($id);


        $response = [
            'isProfileOwner' => $owner->getId() == $loggedin_user->getId(),
            'page_title' => 'Profil',
            'owner' => $owner,
        ];

        // prepare panel data
        $panel_data = null;
        if (0 != count($owner->getRuns())) {
            $day_amount = 1;
            $days_since_first_run = 0;
            $overall_distance = 0;

            foreach ($owner->getRuns() as $key => $value) {
                if ($key > 0 and $owner->getRuns()[$key - 1]->getDate() == $value->getDate()) {
                    $day_amount++;
                }
                $overall_distance += $value->getDistance();
            }
            $now = time();
            $datediff = $now - $owner->getRuns()[count($owner->getRuns()) - 1]->getDate()->getTimestamp();
            $days_since_first_run = floor($datediff / (60 * 60 * 24));

            $panel_data = array(
                'day_amount' => $day_amount,
                'days_since_first_run' => $days_since_first_run,
                'overall_distance' => $overall_distance,
                'canView' => $this->isGranted('view', $owner->getRuns())
            );

        } else {
            $panel_data = array(
                'day_amount' => 0,
                'days_since_first_run' => 0,
                'overall_distance' => 0
            );
        }
        $response['panel_data'] = $panel_data;
        // end prepare

        // prepare table data
        $table_data = [];
        foreach ($owner->getRuns() as $key => $value) {
            $table_data[] = [
                'id' => $value->getId(),
                'date' => $value->getDate()->format('d.m.Y'),
                'distance' => $value->getDistance(),
                'time' => $value->getTime()->format('H:i'),
                'average_speed' => $value->getDistance() / ($value->getTime()->getTimestamp() / 3600),
                'canView' => $this->isGranted('view', $owner->getRuns()),
                'canDelete' => $this->isGranted('delete', $owner->getRuns())
            ];
        }
        $response['table_data'] = $table_data;
        // end prepare


        // form
        $form = $this->createFormBuilder()
            ->setMethod('POST')
            ->add($translator->trans('Time'), TimeType::class, array(
                //'input' => 'timestamp',
                'widget' => 'choice'
            ))
            ->add('Date', DateType::class, array(
                'format' => 'dd MM yyyy'

            ))
            ->add('Distance', IntegerType::class)
            ->add('Save', SubmitType::class, array('label' => 'Create entry'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $distance = $form['Distance']->getData();
            $date = $form['Date']->getData();
            $time = $form['Time']->getData();
            $entry = new RunEntity();
            $entry->setDate($date);
            $entry->setDistance($distance);
            $entry->setTime($time);
            $entry->setUser($owner);
            $entry->validate();
            $validator = $this->get('validator');
            $errors = $validator->validate($entry);

            $validator = $this->get('validator');
            $errors = $validator->validate($entry);

            if (count($errors) > 0) {
                /*
                 * Uses a __toString method on the $errors variable which is a
                 * ConstraintViolationList object. This gives us a nice string
                 * for debugging.
                 */
                $errorsString = (string)$errors;
                $response[] = ['errors' => $errors];

                return new Response($errorsString);
            } else {
                $this->denyAccessUnlessGranted('add', $entry);
                $manager->persist($entry);
                $manager->flush();
            }

        }
        $this->denyAccessUnlessGranted('view', $owner); //TODO implement access deny for unauthorized users
        $response['form'] = $form->createView();
        // end form

        return $response;
    }

    /**
     * @Route("/profile/delete/{run_id}", name="delete")
     */
    public function delete($run_id)
    {
        $run = $this->getDoctrine()->getRepository('App:Entity\RunEntity')->find($run_id);
        $this->denyAccessUnlessGranted('delete', $run);
        $this->getDoctrine()->getRepository('App:Entity\RunEntity')->deleteRun($run_id);
    }
}