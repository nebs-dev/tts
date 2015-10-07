<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * User
 * @ORM\Table(name="personas")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\PersonaRepository")
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class Persona {
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"all"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Expose
     * @Groups({"all"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Expose
     * @Groups({"all"})
     */
    private $occupation;

    /**
     * @ORM\Column(type="text")
     * @Expose
     * @Groups({"all"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Expose
     * @Groups({"all"})
     */
    private $wikipedia;

    /**
     * @ORM\Column(type="float", precision=20, scale=18)
     */
    private $lng;

    /**
     * @ORM\Column(type="float", precision=20, scale=18)
     */
    private $lat;

    /**
     * @ORM\ManyToMany(targetEntity="Place", inversedBy="personas")
     * @Expose
     * @MaxDepth(2)
     */
    private $places;

    /**
     * @ORM\OneToMany(targetEntity="PersonaGallery", mappedBy="persona", cascade={"all"})
     * @Expose
     * @Groups({"all"})
     */
    private $gallery;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="favouritePersonas")
     * @Expose
     * @Groups({"all"})
     * @MaxDepth(2)
     */
    private $favouriteUsers;

    /**
     * created Time/Date
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     * @Expose
     * @Groups({"all"})
     */
    private $createdAt;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set createdAt
     * @ORM\PrePersist
     */
    public function setCreatedAt() {
        $this->createdAt = new \DateTime();
    }

    public function getCreatedAt() {
        return $this->createdAt;
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
     * Set lng
     *
     * @param float $lng
     *
     * @return Persona
     */
    public function setLng($lng) {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float
     */
    public function getLng() {
        return $this->lng;
    }

    /**
     * Set lat
     *
     * @param float $lat
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
     * @return float
     */
    public function getLat() {
        return $this->lat;
    }

    /**
     * Add gallery
     *
     * @param \AppBundle\Entity\PersonaGallery $gallery
     *
     * @return Persona
     */
    public function addGallery(\AppBundle\Entity\PersonaGallery $gallery) {
        $this->gallery[] = $gallery;

        return $this;
    }

    /**
     * Remove gallery
     *
     * @param \AppBundle\Entity\PersonaGallery $gallery
     */
    public function removeGallery(\AppBundle\Entity\PersonaGallery $gallery) {
        $this->gallery->removeElement($gallery);
    }

    /**
     * Get gallery
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGallery() {
        return $this->gallery;
    }

    /**
     * Add favouriteUser
     *
     * @param \AppBundle\Entity\User $favouriteUser
     *
     * @return Persona
     */
    public function addFavouriteUser(\AppBundle\Entity\User $favouriteUser) {
        $this->favouriteUsers[] = $favouriteUser;

        return $this;
    }

    /**
     * Remove favouriteUser
     *
     * @param \AppBundle\Entity\User $favouriteUser
     */
    public function removeFavouriteUser(\AppBundle\Entity\User $favouriteUser) {
        $this->favouriteUsers->removeElement($favouriteUser);
    }

    /**
     * Get favouriteUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavouriteUsers() {
        return $this->favouriteUsers;
    }
}
