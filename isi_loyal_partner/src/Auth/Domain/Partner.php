<?php

namespace Auth\Domain;

use Auth\Domain\Exception\UserAlreadyExist;
use Auth\Domain\Service\CheckIfEmailAlreadyRegistered;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'partners', indexes: [new Index(columns: ['email'], name: 'idx_email')])]
class Partner implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email;

    #[ORM\Column(length: 180, unique: false)]
    private ?string $emailCanonical;

    #[ORM\Column]
    private ?string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->emailCanonical = EmailCanonicalizer::canonicalize($email);
        $this->password = $password;
    }

    /** @throws UserAlreadyExist */
    public static function createNewForRegistration(
        CheckIfEmailAlreadyRegistered $checkIfEmailAlreadyRegistered,
        string $email,
        string $password
    ): self {
        if (($checkIfEmailAlreadyRegistered)(EmailCanonicalizer::canonicalize($email))) {
            throw new UserAlreadyExist();
        }

        return new self($email, $password);
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function hashPassword(UserPasswordHasherInterface $hasher): void
    {
        $this->password = $hasher->hashPassword($this, $this->password);
    }

    /** @see PasswordAuthenticatedUserInterface */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}