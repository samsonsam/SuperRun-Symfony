<?php
/**
 * Created by PhpStorm.
 * MyUserEntity: samuelerb
 * Date: 18.12.17
 * Time: 02:41
 */

namespace App\DataFixtures;


use App\Entity\MyUserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MyUserFixture extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager) {
        $user1 = new MyUserEntity();
        $user1->setUsername('Samuel Erb');
        $user1->setEmail('samuel.erb@gmx.com');
        $user1->setPassword($this->encoder->encodePassword($user1, 'passwort123'));
        $user1->setRoles(array('ROLE_USER', 'ROLE_ADMIN'));
        $manager->persist($user1);
        $manager->flush();

        $user2 = new MyUserEntity();
        $user2->setUsername('Hans Peter');
        $user2->setEmail('samuel.erb@me.com');
        $user2->setPassword($this->encoder->encodePassword($user2, 'passwort123'));
        $user2->setRoles(array('ROLE_USER'));
        $manager->persist($user2);
        $manager->flush();

        /* ... */
    }
}