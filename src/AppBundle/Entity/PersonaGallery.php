<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PersonaGallery
 *
 * @ORM\Table(name="persona_gallery")
 * @ORM\Entity()
 */
class PersonaGallery {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="Persona", inversedBy="gallery")
     */
    private $persona;


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set image
     * @param string $image
     * @return PersonaGallery
     */
    public function setImage($image) {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     * @return string
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Set persona
     *
     * @param \AppBundle\Entity\Persona $persona
     *
     * @return PersonaGallery
     */
    public function setPersona(\AppBundle\Entity\Persona $persona = null) {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \AppBundle\Entity\Persona
     */
    public function getPersona() {
        return $this->persona;
    }
}
