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
     * @ORM\Column(type="decimal", precision=20, scale=18)
     */
    private $long;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=18)
     */
    private $lat;

    /**
     * @ORM\ManyToMany(targetEntity="Place", inversedBy="personas")
     */
    private $places;


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

    /**
     * Constructor
     */
    public function __construct() {
        $this->places = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add place
     *
     * @param \AppBundle\Entity\Place $place
     *
     * @return Persona
     */
    public function addPlace(\AppBundle\Entity\Place $place) {
        $this->places[] = $place;

        return $this;
    }

    /**
     * Remove place
     *
     * @param \AppBundle\Entity\Place $place
     */
    public function removePlace(\AppBundle\Entity\Place $place) {
        $this->places->removeElement($place);
    }

    /**
     * Get places
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlaces() {
        return $this->places;
    }

    /**
     * Set long
     *
     * @param string $long
     *
     * @return Persona
     */
    public function setLong($long) {
        $this->long = $long;

        return $this;
    }

    /**
     * Get long
     *
     * @return string
     */
    public function getLong() {
        return $this->long;
    }

    /**
     * Set lat
     *
     * @param string $lat
     *
     * @return Persona
     */
    public function setLat($lat) {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat() {
        return $this->lat;
    }
}
