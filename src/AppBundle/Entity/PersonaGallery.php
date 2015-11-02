<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * PersonaGallery
 *
 * @ORM\Table(name="persona_gallery")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class PersonaGallery {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @ORM\Column(name="image", type="string", length=255)
     * @Expose
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="Persona", inversedBy="gallery")
     */
    private $persona;


    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        $file = $this->getAbsolutePath();
        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function getAbsolutePath() {
        return null === $this->image
            ? null
            : $this->getUploadRootDir() . '/' . $this->image;
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../web';
    }


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
