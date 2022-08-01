<?php

namespace App\Entity;

use App\Domain\MovieOwnerId;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];
    /**
     * @ORM\OneToOne(targetEntity=MovieOwner::class, mappedBy="identity", cascade={"persist", "remove"})
     */
    private MovieOwner $movieOwner;

    public function __construct(
        /**
         * @ORM\Column(type="string", length=180, unique=true, nullable=false)
         */
        private string $email,
        ?MovieOwner $movieOwner = null
    )
    {
        $this->setMovieOwner( $movieOwner ?? new MovieOwner());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     *
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     *
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     *
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = "ROLE_USER";

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     *
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return '$argon2id$v=19$m=65536,t=4,p=1$gX5PUU5Zbee7AYV9Rpmkhg$mYgyPUq9jg2w4T3ACo9ufITG4fvlaElaGGEFatLtpXs';//1
    }

    /**
     *
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     *
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getMovieOwnerId(): MovieOwnerId
    {
        return $this->movieOwner->getId();
    }

    private function setMovieOwner(MovieOwner $movieOwner): self
    {
        $movieOwner->setIdentity($this);
        $this->movieOwner = $movieOwner;

        return $this;
    }
}
