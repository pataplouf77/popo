<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="product")
 * @Vich\Uploadable()
 */

class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer" ,  length=191)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;
    
    /**
	 * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $filename;
    
    /**
  	* @Assert\File(
	*     maxSize = "300K",
	*     mimeTypes = {"image/jpeg"},
	*     maxSizeMessage = "The maxmimum allowed file size is 300 Kilo.",
	*     mimeTypesMessage = "Only the filetype image are allowed."
	* ) 
    * @Vich\UploadableField(mapping="product_image", fileNameProperty="filename")
    */
    
	private $imageFile;
	
	/**
     * @ORM\Column(type="string", length=30)
     */
    private $taille;
	
	/**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="products")
     */
    private $tags;
	
	/**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;
	
	public function __construct()
    {
        $this->updatedAt = new \DateTime();
		$this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
	}
	/**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }
	//
	public function setTags(?Tags $tags): self
    {
        $this->tags = $tags;
        return $this;
    }
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addProduct($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeProduct($this);
        }

        return $this;
    } 
	//
	/**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     * @return Product
     */
    public function setFilename(?string $filename): Product
    {
        $this->filename = $filename;
        return $this;
    }
	//
    //
    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
	//
	/**
     * @param File|null $imageFile
     * @return Product
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): Product
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    //
    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }
	public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(?string $taille): self
    {
        $this->taille = $taille;

        return $this;
    }
}
