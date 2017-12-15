<?php
/**
 * Created by IntelliJ IDEA.
 * User: samuelerb
 * Date: 15.12.17
 * Time: 17:38
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * @ORM\Column(type="string")
     */
    private $price;
    /**
     * @ORM\Column(type="string")
     */
    private $status;
    /** @ORM\Column(type="text")
     */
    private $text;

    public function __construct($name, $price = "20 €/h", $status = Availability::AVAILABLE,
                                $text = null) {
        $this->name = $name;
        $this->price = $price;
        $this->setStatus($status);
        $this->text = $text ?? "Buchen Sie $name noch heute.";
    }

    public function getNameStatus() {
        return $this->getName() . ($this->getStatus() != Availability::AVAILABLE ? ' (' . $this->getStatus() . ')' : '');
    }

    public function isBookable() {
        return $this->getStatus() != Availability::FULLY_BOOKED;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        if (!in_array($status, Availability::getStates())) {
            throw new \InvalidArgumentException("Invalid type");
        }

        $this->status = $status;
    }

    public function getText() {
        return $this->text;
    }

    public function setText($text) {
        $this->text = $text;
    }
}