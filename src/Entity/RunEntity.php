<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use UnexpectedValueException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * * @ORM\Table(name="runs")
 * @ORM\Entity(repositoryClass="App\Repository\RunEntityRepository")
 */
class RunEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MyUserEntity", inversedBy="runs")
     */
    private $user;

    /**
     * @var
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\Type("datetime")
     * @Assert\LessThan("today")
     */
    private $date;

    /**
     * @var
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 40,
     *      minMessage = "You running faster than {{ limit }} km/h ?",
     *      maxMessage = "You running faster than {{ limit }} km/h ?"
     * )
     * @Assert\Type("integer")
     */
    private $distance;

    /**
     * @var
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $time;

    /**
     * RunEntity constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {

        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $now = new DateTime();
        if (!isset($date)) {
            throw new UnexpectedValueException('$Date was not set!');
        } elseif ($date > $now) {
            throw new UnexpectedValueException('$Date was invalid!');
        } else {
            $this->date = $date;
        }
    }

    /**
     * @return mixed
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param mixed $distance
     */
    public function setDistance($distance): void
    {
        $distance = intval($distance);
        if (!isset($distance)) {
            throw new UnexpectedValueException('$Distance was not set!');
        } elseif (!is_numeric($distance)) {
            throw new UnexpectedValueException('$Distance was not numeric!');
        }elseif ($distance <= 0) {
            throw new UnexpectedValueException('$Distance can not be \'0\'!');
        } else {
            $this->distance = $distance;
        }
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    public function validate()
    {
        if ( ($this->distance / ($this->time->getTimestamp()/3600)) > 40) {
            throw new \Exception('Speed can not be above 40 km/h');
        }
    }
}
