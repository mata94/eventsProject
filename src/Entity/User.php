<?php

namespace App\Entity;

use App\Helper\DateTimeHelper;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity()]
#[ORM\HasLifecycleCallbacks()]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_ADMIN = "ROLE_ADMIN";
    public const ROLE_USER = "ROLE_USER";
    public const ROLE_GUEST = "ROLE_GUEST";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 180)]
    #[ORM\JoinColumn(nullable: false)]
    private string $email;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[ORM\Column]
    #[ORM\JoinColumn(nullable: false)]
    private bool $verified;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userToken = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $tokenExpiration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, SlotUser>
     */
    #[ORM\OneToMany(targetEntity: SlotUser::class, mappedBy: 'user')]
    private Collection $slotUsers;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullName = null;

    public function __construct()
    {
        $this->slotUsers = new ArrayCollection();
    }

    /**
     * @param string $email
     * @param string[] $roles
     * @return self
     */
    public static function make(
        string $email,
        array $roles,
    ): self {
        $self = new self();
        $self->setEmail($email);
        $self->setRoles($roles);
        $self->setVerified(true);
        return $self;
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        $time = DateTimeHelper::getNewForImmutable();
        $createdAt = DateTimeHelper::getNewUserTimeForImmutable($time);
        $this->setCreatedAt($createdAt);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): static
    {
        $this->verified = $verified;

        return $this;
    }

    public function getUserToken(): ?string
    {
        return $this->userToken;
    }

    public function setUserToken(?string $userToken): static
    {
        $this->userToken = $userToken;

        return $this;
    }

    public function getTokenExpiration(): ?\DateTimeInterface
    {
        return $this->tokenExpiration;
    }

    public function setTokenExpiration(?\DateTimeInterface $tokenExpiration): static
    {
        $this->tokenExpiration = $tokenExpiration;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, SlotUser>
     */
    public function getSlotUsers(): Collection
    {
        return $this->slotUsers;
    }

    public function addSlotUser(SlotUser $slotUser): static
    {
        if (!$this->slotUsers->contains($slotUser)) {
            $this->slotUsers->add($slotUser);
            $slotUser->setUser($this);
        }

        return $this;
    }

    public function removeSlotUser(SlotUser $slotUser): static
    {
        if ($this->slotUsers->removeElement($slotUser)) {
            // set the owning side to null (unless already changed)
            if ($slotUser->getUser() === $this) {
                $slotUser->setUser($this);
            }
        }

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }
}
