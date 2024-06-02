<?php

namespace Auth\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'clients', indexes: [new Index(columns: ['email'], name: 'idx_email')])]
class Client implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email;

    #[ORM\Column(length: 180, unique: false)]
    private ?string $emailCanonical;

    #[ORM\Column]
    private ?string $password;

    public function __construct(string $email, string $password = null)
    {
        $this->email = $email;
        $this->emailCanonical = EmailCanonicalizer::canonicalize($email);
        $this->password = $password;
    }

    public function isEqualTo(UserInterface $user): bool
    {
        return $user->getUserIdentifier() === $this->getUserIdentifier();
    }

    public function isMustToCompleteRegistration(): bool
    {
        return is_null($this->password);
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function updatePassword(UserPasswordHasherInterface $hasher, string $password): void
    {
        $this->password = $hasher->hashPassword($this, $password);
    }

    /** @see PasswordAuthenticatedUserInterface */
    public function getPassword(): string
    {
        return $this->password;
    }

    /** @see UserInterface */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}