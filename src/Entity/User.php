<?php
// /src/Entity/User.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields="email", message="Email déjà pris")
 * @UniqueEntity(fields="username", message="Username déjà pris")
 * @Vich\Uploadable()
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer" ,  length=191 )
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string" , length=30)
     * @Assert\NotBlank()
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30 , unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30 , unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

	/**
	 * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $plainPassword;

	
    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $niveau;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $certif;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $licence;

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
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;
	//
	public function __construct()
    {
        $this->updatedAt = new \DateTime();
		$this->updatedAt2 = new \DateTime();
    }
	//seconde image
	/**
	 * @var string
     * @ORM\Column(type="string", length=255)
     */
	private $filename2;
	
	/**
  	* @Assert\File(
	*     maxSize = "300K",
	*     mimeTypes = {"image/jpeg"},
	*     maxSizeMessage = "The maxmimum allowed file size is 300 Kilo.",
	*     mimeTypesMessage = "Only the filetype image are allowed."
	* ) 
    * @Vich\UploadableField(mapping="product_image2", fileNameProperty="filename2")
    */
    
	private $imageFile2;
	
	/**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt2;
	//
	//public function __construct()
    //{
    //    $this->updatedAt2 = new \DateTime();
    //}
	
	
	//
	public function getId(): int
    {
        return $this->id;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    // le ? signifie que cela peur aussi retourner null
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
	
	function getPlainPassword() {
                                   return $this->plainPassword;
                               }
	
	function setPlainPassword($plainPassword) {
                                   $this->plainPassword = $plainPassword;
                               }
	
    /**
     * Retourne les rôles de l'user
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // Afin d'être sûr qu'un user a toujours au moins 1 rôle
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Retour le salt qui a servi à coder le mot de passe
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // See "Do you need to use a Salt?" at https://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one

        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // Nous n'avons pas besoin de cette methode car nous n'utilions pas de plainPassword
        // Mais elle est obligatoire car comprise dans l'interface UserInterface
        // $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([$this->id, $this->username, $this->password]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getCertif(): ?string
    {
        return $this->certif;
    }

    public function setCertif(string $certif): self
    {
        $this->certif = $certif;

        return $this;
    }

    public function getLicence(): ?string
    {
        return $this->licence;
    }

    public function setLicence(string $licence): self
    {
        $this->licence = $licence;

        return $this;
    }
	
	//premiere image 
	
	/**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     * @return User
     */
    public function setFilename(?string $filename): User
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
     * @return User
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): User
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
	//seconde image
	/**
     * @return string|null
     */
    public function getFilename2(): ?string
    {
        return $this->filename2;
    }

    /**
     * @param string|null $filename2
     * @return User
     */
    public function setFilename2(?string $filename2): User
    {
        $this->filename2 = $filename2;
        return $this;
    }
	//
    //
    /**
     * @return File|null
     */
    public function getImageFile2(): ?File
    {
        return $this->imageFile2;
    }
	//
	/**
     * @param File|null $imageFile2
     * @return User
     * @throws \Exception
     */
    public function setImageFile2(?File $imageFile2): User
    {
        $this->imageFile2 = $imageFile2;
        if ($this->imageFile2 instanceof UploadedFile) {
            $this->updated_at2 = new \DateTime('now');
        }
        return $this;
    }

    public function getUpdatedAt2(): ?\DateTimeInterface
    {
        return $this->updatedAt2;
    }

    public function setUpdatedAt2(?\DateTimeInterface $updatedAt2): self
    {
        $this->updatedAt2 = $updatedAt2;

        return $this;
    }	
	
}
