<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * User
 * @ORM\Table(name="places")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\PlaceRepository")
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class Place {
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
     * @ORM\Column(type="text")
     * @Expose
     * @Groups({"all"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=100)
     * @Expose
     * @Groups({"all"})
     */
    private $address;

    /**
     * @ORM\Column(type="float", precision=20, scale=18)
     */
    private $lat;

    /**
     * @ORM\Column(type="float", precision=20, scale=18)
     */
    private $lng;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="place", cascade={"all"})
     * @Expose
     * @Groups({"all"})
     * @MaxDepth(2)
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Persona", mappedBy="places")
     * @Expose
     * @MaxDepth(2)
     */
    private $personas;

    /**
     * @ORM\OneToMany(targetEntity="PlaceGallery", mappedBy="place", cascade={"all"})
     * @Expose
     * @Groups({"all"})
     * @MaxDepth(2)
     */
    private $gallery;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="favouritePlaces")
     * @Expose
     * @Groups({"all"})
     * @MaxDepth(5)
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
     * @return Place
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
     * Set description
     *
     * @param string $description
     *
     * @return Place
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
     * Set address
     *
     * @param string $address
     *
     * @return Place
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Place
     */
    public function addComment(\AppBundle\Entity\Comment $comment) {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(\AppBundle\Entity\Comment $comment) {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments() {
        return $this->comments;
    }

    /**
     * Add persona
     *
     * @param \AppBundle\Entity\Persona $persona
     *
     * @return Place
     */
    public function addPersona(\AppBundle\Entity\Persona $persona) {
        $this->personas[] = $persona;

        return $this;
    }

    /**
     * Remove persona
     *
     * @param \AppBundle\Entity\Persona $persona
     */
    public function removePersona(\AppBundle\Entity\Persona $persona) {
        $this->personas->removeElement($persona);
    }

    /**
     * Get personas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersonas() {
        return $this->personas;
    }


    /**
     * Set lat
     *
     * @param float $lat
     *
     * @return Place
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
     * Set lng
     *
     * @param float $lng
     *
     * @return Place
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
     * Add gallery
     *
     * @param \AppBundle\Entity\PlaceGallery $gallery
     *
     * @return Place
     */
    public function addGallery(\AppBundle\Entity\PlaceGallery $gallery) {
        $this->gallery[] = $gallery;

        return $this;
    }

    /**
     * Remove gallery
     *
     * @param \AppBundle\Entity\PlaceGallery $gallery
     */
    public function removeGallery(\AppBundle\Entity\PlaceGallery $gallery) {
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
     * @return Place
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
