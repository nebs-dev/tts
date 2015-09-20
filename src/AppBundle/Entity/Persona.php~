<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 * @ORM\Table(name="personas")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\PersonaRepository")
 */
class Persona {
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $occupation;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $wikipedia;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Persona
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set occupation
     *
     * @param string $occupation
     *
     * @return Persona
     */
    public function setOccupation($occupation) {
        $this->occupation = $occupation;

        return $this;
    }

    /**
     * Get occupation
     *
     * @return string
     */
    public function getOccupation() {
        return $this->occupation;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Persona
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set wikipedia
     *
     * @param string $wikipedia
     *
     * @return Persona
     */
    public function setWikipedia($wikipedia) {
        $this->wikipedia = $wikipedia;

        return $this;
    }

    /**
     * Get wikipedia
     *
     * @return string
     */
    public function getWikipedia() {
        return $this->wikipedia;
    }
}
