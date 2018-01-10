<?php
/**
 * Created by IntelliJ IDEA.
 * MyUserEntity: samuelerb
 * Date: 15.12.17
 * Time: 17:38
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class MyUserEntity implements UserInterface, \Serializable
{
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RunEntity", mappedBy="user")
     */
    private $runs;


    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function __construct()
    {
        $this->isActive = true;
        $this->runs = new ArrayCollection();
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getRuns()
    {
        return $this->runs;
    }

    /**
     * @param mixed $runs
     */
    public function setRuns($runs): void
    {
        $this->runs = $runs;
    }

    /**
     * @return mixed
     */
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }


    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->runs
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->runs
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    function getPanelData()
    {
        if (0 != count($this->runs)) {
            $day_amount = 1;
            $days_since_first_run = 0;
            $overall_distance = 0;

            foreach ($this->runs as $key => $value) {
                if ($key > 0 and $this->runs[$key - 1]->getDate() == $value->getDate()) {
                    $day_amount++;
                }
                $overall_distance += $value->getDistance();
            }
            $now = time();
            $datediff = $now - $this->runs[count($this->runs) - 1]->getDate()->getTimestamp();
            $days_since_first_run = floor($datediff / (60 * 60 * 24));

            return array(
                'day_amount' => $day_amount,
                'days_since_first_run' => $days_since_first_run,
                'overall_distance' => $overall_distance
            );

        } else {
            return array(
                'day_amount' => 0,
                'days_since_first_run' => 0,
                'overall_distance' => 0
            );
        }


    }

    function getOverviewData()
    {
        if (0 != count($this->getRuns())) {
            $day_amount = 1;
            $overall_distance = 0;

            foreach ($this->getRuns() as $key => $value) {
                if ($key > 0 and $this->getRuns()[$key - 1]->getDate() == $value->getDate()) {
                    $day_amount++;
                }
                $overall_distance += $value->getDistance();
            }

            return array(
                'id' => $this->getId(),
                'name' => $this->getUsername(),
                'day_amount' => $day_amount,
                'overall_distance' => $overall_distance
            );

        } else {
            return array(
                'id' => $this->getId(),
                'name' => $this->getUsername(),
                'day_amount' => 0,
                'overall_distance' => 0
            );
        }
    }

    function getTableData()
    {
        $data = [];
        foreach ($this->runs as $key => $value) {
            $data[] = [
                'id' => $value->getId(),
                'date' => $value->getDate()->format('d.m.Y'),
                'distance' => $value->getDistance(),
                'time' => $value->getTime()->format('H:i'),
                'average_speed' => $value->getDistance() / ($value->getTime()->getTimestamp() / 3600)
            ];
        }
        return $data;
    }

    public function getSalt()
    {
        return null;
        // TODO: Implement getSalt() method.
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}