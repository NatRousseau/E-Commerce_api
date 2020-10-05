<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdersRepository::class)
 */
class Orders
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $billing_adress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $billing_city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $billing_postal_code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shipping_adress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shipping_city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shipping_postal_code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $paiement_status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getBillingAdress(): ?string
    {
        return $this->billing_adress;
    }

    public function setBillingAdress(string $billing_adress): self
    {
        $this->billing_adress = $billing_adress;

        return $this;
    }

    public function getBillingCity(): ?string
    {
        return $this->billing_city;
    }

    public function setBillingCity(string $billing_city): self
    {
        $this->billing_city = $billing_city;

        return $this;
    }

    public function getBillingPostalCode(): ?string
    {
        return $this->billing_postal_code;
    }

    public function setBillingPostalCode(string $billing_postal_code): self
    {
        $this->billing_postal_code = $billing_postal_code;

        return $this;
    }

    public function getShippingAdress(): ?string
    {
        return $this->shipping_adress;
    }

    public function setShippingAdress(string $shipping_adress): self
    {
        $this->shipping_adress = $shipping_adress;

        return $this;
    }

    public function getShippingCity(): ?string
    {
        return $this->shipping_city;
    }

    public function setShippingCity(string $shipping_city): self
    {
        $this->shipping_city = $shipping_city;

        return $this;
    }

    public function getShippingPostalCode(): ?string
    {
        return $this->shipping_postal_code;
    }

    public function setShippingPostalCode(string $shipping_postal_code): self
    {
        $this->shipping_postal_code = $shipping_postal_code;

        return $this;
    }

    public function getPaiementStatus(): ?string
    {
        return $this->paiement_status;
    }

    public function setPaiementStatus(string $paiement_status): self
    {
        $this->paiement_status = $paiement_status;

        return $this;
    }
}
