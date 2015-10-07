<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

/**
 * User
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User {
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(name="photo", type="text", nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(name="platform", type="integer", options={"comment":"2 == android, 1 == ios"})
     */
    private $platform;

    /**
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user", cascade={"all"})
     */
    private $comments;

    /**
     * @ORM\OneToOne(targetEntity="Facebook", cascade={"all"})
     */
    private $facebook;

    /**
     * @ORM\OneToOne(targetEntity="Twitter", cascade={"all"})
     */
    private $twitter;

    /**
     * @ORM\ManyToMany(targetEntity="Place", mappedBy="favouriteUsers", cascade={"all"})
     * @ORM\JoinTable(name="place_favourites")
     */
    private $favouritePlaces;

    /**
     * @ORM\ManyToMany(targetEntity="Persona", mappedBy="favouriteUsers", cascade={"all"})
     * @ORM\JoinTable(name="persona_favourites")
     */
    private $favouritePersonas;

    /**
     * @ORM\ManyToMany(targetEntity="Persona", mappedBy="likeUsers", cascade={"all"})
     * @ORM\JoinTable(name="persona_likes")
     */
    private $likePersonas;


    /**
     * @ORM\PrePersist
     */
    public function encryptPassword() {
        if(!is_null($this->password))
            $this->password = password_hash($this->password, PASSWORD_BCRYPT, array('cost' => 12));
    }


    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     * @param string $name
     * @return User
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set email
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set password
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set photo
     * @param string $photo
     * @return User
     */
    public function setPhoto($photo) {
        $this->photo = $photo;
        return $this;
    }

    /**
     * Get photo
     * @return string
     */
    public function getPhoto() {
        return $this->photo;
    }

    /**
     * Set platform
     * @param boolean $platform
     * @return User
     */
    public function setPlatform($platform) {
        $this->platform = $platform;
        return $this;
    }

    /**
     * Get platform
     * @return boolean
     */
    public function getPlatform() {
        return $this->platform;
    }

    /**
     * Set token
     * @param string $token
     * @return User
     */
    public function setToken($token) {
        $this->token = $token;
        return $this;
    }

    /**
     * Get token
     * @return string
     */
    public function getToken() {
        return $this->token;
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
     * @return User
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
     * Set facebook
     *
     * @param \AppBundle\Entity\Facebook $facebook
     *
     * @return User
     */
    public function setFacebook(\AppBundle\Entity\Facebook $facebook = null) {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return \AppBundle\Entity\Facebook
     */
    public function getFacebook() {
        return $this->facebook;
    }

    /**
     * Set twitter
     *
     * @param \AppBundle\Entity\Twitter $twitter
     *
     * @return User
     */
    public function setTwitter(\AppBundle\Entity\Twitter $twitter = null) {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return \AppBundle\Entity\Twitter
     */
    public function getTwitter() {
        return $this->twitter;
    }

    /**
     * Add favouritePlace
     *
     * @param \AppBundle\Entity\Place $favouritePlace
     *
     * @return User
     */
    public function addFavouritePlace(\AppBundle\Entity\Place $favouritePlace)
    {
        $this->favouritePlaces[] = $favouritePlace;

        return $this;
    }

    /**
     * Remove favouritePlace
     *
     * @param \AppBundle\Entity\Place $favouritePlace
     */
    public function removeFavouritePlace(\AppBundle\Entity\Place $favouritePlace)
    {
        $this->favouritePlaces->removeElement($favouritePlace);
    }

    /**
     * Get favouritePlaces
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavouritePlaces()
    {
        return $this->favouritePlaces;
    }

    /**
     * Add favouritePersona
     *
     * @param \AppBundle\Entity\Persona $favouritePersona
     *
     * @return User
     */
    public function addFavouritePersona(\AppBundle\Entity\Persona $favouritePersona)
    {
        $this->favouritePersonas[] = $favouritePersona;

        return $this;
    }

    /**
     * Remove favouritePersona
     *
     * @param \AppBundle\Entity\Persona $favouritePersona
     */
    public function removeFavouritePersona(\AppBundle\Entity\Persona $favouritePersona)
    {
        $this->favouritePersonas->removeElement($favouritePersona);
    }

    /**
     * Get favouritePersonas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavouritePersonas()
    {
        return $this->favouritePersonas;
    }

    /**
     * Add likePersona
     *
     * @param \AppBundle\Entity\Persona $likePersona
     *
     * @return User
     */
    public function addLikePersona(\AppBundle\Entity\Persona $likePersona)
    {
        $this->likePersonas[] = $likePersona;

        return $this;
    }

    /**
     * Remove likePersona
     *
     * @param \AppBundle\Entity\Persona $likePersona
     */
    public function removeLikePersona(\AppBundle\Entity\Persona $likePersona)
    {
        $this->likePersonas->removeElement($likePersona);
    }

    /**
     * Get likePersonas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikePersonas()
    {
        return $this->likePersonas;
    }
}
