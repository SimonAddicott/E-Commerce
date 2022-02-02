<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $payment_ref;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $basket;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $payment_details;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?string
    {
        return $this->datetime->format('H:i d-m-Y');
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getPaymentRef(): ?string
    {
        return $this->payment_ref;
    }

    public function setPaymentRef(?string $payment_ref): self
    {
        $this->payment_ref = $payment_ref;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getBasket(): ?string
    {
        return $this->basket;
    }

    public function setBasket(?string $basket): self
    {
        $this->basket = $basket;

        return $this;
    }

    public function getPaymentDetails(): ?string
    {
        return $this->payment_details;
    }

    public function setPaymentDetails(?string $payment_details): self
    {
        $this->payment_details = $payment_details;

        return $this;
    }
    
    public function getStatus(): ?string
    {
        return $this->status;
    }
    
    public function setStatus(?string $status): self
    {
        $this->status = $status;
        
        return $this;
    }
}
