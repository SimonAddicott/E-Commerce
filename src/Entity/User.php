<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;
    
    /**
     * @ORM\Column(type="string", length=180)
     */
    private $firstName;
    
    /**
     * @ORM\Column(type="string", length=180)
     */
    private $lastName;
    
    /**
     * @ORM\Column(type="string", length=180)
     */
    private $addressLineOne;
    
    /**
     * @ORM\Column(type="string", length=180)
     */
    private $addressLineTwo;
    
    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $addressLineThree;
    
    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $addressCity;
    
    /**
     * @ORM\Column(type="string", length=180,nullable=true)
     */
    private $addressCounty;
    
    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $addressPostcode;
    
    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $addressCountryCode;
    
    

    public function getId(): ?int
    {
        return $this->id;
    }
    
    

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    
    

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }
    
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        
        return $this;
    }
    
    
    
    public function getLastName(): ?string
    {
        return $this->lastName;
    }
    
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        
        return $this;
    }
    
    public function getAddressLineOne(): ?string
    {
        return $this->addressLineOne;
    }
    
    public function setAddressLineOne(string $addressOne): self
    {
        $this->addressLineOne = $addressOne;
        
        return $this;
    }
    
    public function getAddressLineTwo(): ?string
    {
        return $this->addressLineTwo;
    }
    
    public function setAddressLineTwo(string $addressTwo): self
    {
        $this->addressLineTwo = $addressTwo;
        
        return $this;
    }
    
    public function getAddressLineThree(): ?string
    {
        return $this->addressLineThree;
    }
    
    public function setAddressLineThree(string $addressThree): self
    {
        $this->addressLineThree = $addressThree;
        
        return $this;
    }
    
    public function getAddressCity(): ?string
    {
        return $this->addressCity;
    }
    
    public function setAddressCity(string $addressCity): self
    {
        $this->addressCity= $addressCity;
        
        return $this;
    }
    
    public function getAddressCounty(): ?string
    {
        return $this->addressCounty;
    }
    
    public function setAddressCounty(string $addressCounty): self
    {
        $this->addressCounty = $addressCounty;
        
        return $this;
    }
    
    
    public function getAddressPostcode(): ?string
    {
        return $this->addressPostcode;
    }
    
    public function setAddressPostcode(string $addressPostcode): self
    {
        $this->addressPostcode = $addressPostcode;
        
        return $this;
    }
    
    public function getAddressCountryCode(): ?string
    {
        return $this->addressCountryCode;
    }
    
    public function setAddressCountryCode(string $addressCountryCode): self
    {
        $this->addressCountryCode = $addressCountryCode;
        
        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
