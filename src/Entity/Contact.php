<?php

  namespace App\Entity;
  use Symfony\Component\Validator\Constraints as Assert;

  class Contact {

    #[Assert\NotBlank(message: 'Ce champ ne peut être vide')]
    private ?string $firstName = NULL;

    #[Assert\NotBlank(message: 'Ce champ ne peut être vide')]
    private ?string $lastName = NULL;

    #[Assert\NotBlank(message: 'Ce champ ne peut être vide')]
    #[Assert\Email(message: 'Ce champ doit être un email valide')]
    private ?string $email = NULL;

    #[Assert\NotBlank(message: 'Ce champ ne peut être vide')]
    #[Assert\Length(min: 25, minMessage: 'Ce champ doit faire au minimum {{ limit }} caractères')]
    private ?string $message = NULL;

    /**
     * @return string|null
     */
    public function getFirstName(): ?string {
      return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void {
      $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string {
      return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void {
      $this->lastName = $lastName;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string {
      return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void {
      $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string {
      return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void {
      $this->message = $message;
    }



  }
