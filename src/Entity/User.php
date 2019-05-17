<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity()
 */
class User extends AbstractEntity implements UserInterface
{
    /**
     * @ORM\Column(type="string", length=31, nullable=false, unique=true)
     * @Assert\NotBlank(message="violation.username.blank")
     * @Assert\Regex(
     *     pattern="/^[ a-zA-Z0-9éÉèÈêÊëËäÄâÂàÀïÏöÖôÔüÜûÛçÇ']+$/",
     *     message="violation.username.wrong_format"
     * )
     * @Assert\Length(
     *     min=1,
     *     max=30,
     *     minMessage="violation.username.too_short",
     *     maxMessage="violation.username.too_long"
     * )
     *
     * @var string|null
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     *
     * @Assert\NotBlank(message="violation.email.blank")
     * @Assert\Email(message="violation.email.wrong_format")
     *
     * @var string|null
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=511, nullable=false)
     *
     * @var string|null
     */
    protected $password;

    /**
     * @Assert\NotBlank(message="violation.password.blank")
     * @Assert\Length(
     *     min=8,
     *     max=4000,
     *     minMessage="violation.password.too_short",
     *     maxMessage="violation.password.too_long"
     * )
     *
     * @var string|null
     */
    protected $plainPassword;

    /**
     * @ORM\Column(type="string", length=127, nullable=false)
     *
     * @var string|null
     */
    protected $salt;

    /**
     * @ORM\Column(type="json", length=31, nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @var string[]
     */
    protected $roles;

    /**
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Gedmo\Slug(fields={"username"})
     *
     * @var string|null
     */
    protected $slug;

    public function __construct()
    {
        $this->salt = $this->generateSalt();
        $this->roles = ['ROLE_USER'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->username ?: '';
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * @param string|null $salt
     */
    public function setSalt(?string $salt): void
    {
        $this->salt = $salt;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles, true);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @param string $role
     * @return User
     */
    public function addRole(string $role): User
    {
        $this->roles[] = $role;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('ROLE_ADMIN');
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function generateSalt(): string
    {
        return substr(base64_encode(random_bytes(64)), 8, 72);
    }

    /**
     * @Assert\Callback()
     *
     * @param ExecutionContextInterface $context
     * @param array|null $payload
     *
     * @return void
     */
    public function validateRoles(ExecutionContextInterface $context, array $payload = null): void
    {
        foreach ($this->roles as $role) {
            if (strpos($role, 'ROLE_') !== 0) {
                $context
                    ->buildViolation('violation.roles.wrong_format')
                    ->atPath('roles')
                    ->addViolation();
            }
        }

        if (false === in_array('ROLE_USER', $this->roles, true)) {
            $context
                ->buildViolation('violation.roles.missing_user_role')
                ->atPath('roles')
                ->addViolation();
        }
    }
}
