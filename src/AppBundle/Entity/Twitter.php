<?php
//
//namespace AppBundle\Entity;
//
//use Doctrine\ORM\Mapping as ORM;
//
///**
// * Facebook
// * @ORM\Table(name="twitters")
// * @ORM\Entity()
// */
//class Twitter {
//    /**
//     * @ORM\Column(name="id", type="integer")
//     * @ORM\Id
//     * @ORM\GeneratedValue(strategy="AUTO")
//     */
//    private $id;
//
//    /**
//     * @ORM\Column(type="string")
//     */
//    private $twitterId;
//
//    /**
//     * @ORM\Column(name="email", type="string", length=100, nullable=true)
//     */
//    private $email;
//
//    /**
//     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
//     */
//    private $photo;
//
//    /**
//     * @ORM\OneToOne(targetEntity="User")
//     */
//    private $user;
//
//
//    /**
//     * Get id
//     *
//     * @return integer
//     */
//    public function getId() {
//        return $this->id;
//    }
//
//    /**
//     * Set email
//     *
//     * @param string $email
//     *
//     * @return Twitter
//     */
//    public function setEmail($email) {
//        $this->email = $email;
//
//        return $this;
//    }
//
//    /**
//     * Get email
//     *
//     * @return string
//     */
//    public function getEmail() {
//        return $this->email;
//    }
//
//    /**
//     * Set user
//     *
//     * @param \AppBundle\Entity\User $user
//     *
//     * @return Twitter
//     */
//    public function setUser(\AppBundle\Entity\User $user = null) {
//        $this->user = $user;
//
//        return $this;
//    }
//
//    /**
//     * Get user
//     *
//     * @return \AppBundle\Entity\User
//     */
//    public function getUser() {
//        return $this->user;
//    }
//
//    /**
//     * Set photo
//     *
//     * @param string $photo
//     *
//     * @return Twitter
//     */
//    public function setPhoto($photo) {
//        $this->photo = $photo;
//
//        return $this;
//    }
//
//    /**
//     * Get photo
//     *
//     * @return string
//     */
//    public function getPhoto() {
//        return $this->photo;
//    }
//
//    /**
//     * Set twitterId
//     *
//     * @param string $twitterId
//     *
//     * @return Twitter
//     */
//    public function setTwitterId($twitterId) {
//        $this->twitterId = $twitterId;
//
//        return $this;
//    }
//
//    /**
//     * Get twitterId
//     *
//     * @return string
//     */
//    public function getTwitterId() {
//        return $this->twitterId;
//    }
//}
