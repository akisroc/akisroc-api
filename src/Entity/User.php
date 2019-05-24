<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username", message="violation.username.not_unique")
 * @UniqueEntity("email", message="violation.email.not_unique")
 * @UniqueEntity("slug", message="violation.slug.not_unique")
 */
class User extends AbstractEntity implements UserInterface
{
    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="author")
     *
     * @var Collection
     */
    protected $posts;

    /**
     * @ORM\OneToMany(targetEntity="Protagonist", mappedBy="user")
     *
     * @var Collection
     */
    protected $protagonists;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="from")
     *
     * @var Collection
     */
    protected $sentMessages;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="from")
     *
     * @var Collection
     */
    protected $receivedMessages;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups({"default"})
     *
     * @ORM\Column(type="string", length=511, nullable=true)
     *
     * @Assert\Url(message="violation.uri.wrong_format")
     * @Assert\Length(
     *     min=1,
     *     max=500,
     *     minMessage="violation.uri.too_short",
     *     maxMessage="violation.uri.too_long"
     * )
     *
     * @var string|null
     */
    protected $avatar;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups({"default"})
     *
     * @ORM\Column(type="string", length=31, nullable=false, unique=true)
     * @Assert\NotBlank(message="violation.name.blank")
     * @Assert\Regex(
     *     pattern="/^[ a-zA-Z0-9éÉèÈêÊëËäÄâÂàÀïÏöÖôÔüÜûÛçÇ']+$/",
     *     message="violation.name.invalid_characters"
     * )
     * @Assert\Length(
     *     min=1,
     *     max=30,
     *     minMessage="violation.name.too_short",
     *     maxMessage="violation.name.too_long"
     * )
     *
     * @var string|null
     */
    protected $username;

    /**
     * @Serializer\Expose()
     *
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
     * @Serializer\Expose()
     * @Serializer\Groups({"default"})
     *
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Gedmo\Slug(fields={"username"})
     *
     * @var string|null
     */
    protected $slug;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups({"default"})
     *
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $enabled;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->protagonists = new ArrayCollection();
        $this->sentMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
        $this->salt = $this->generateSalt();
        $this->roles = ['ROLE_USER'];
        $this->enabled = true;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->username ?: '';
    }

    /**
     * @return Collection
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @param Collection $posts
     */
    public function setPosts(Collection $posts): void
    {
        $this->posts = $posts;
    }

    /**
     * @param Post $post
     */
    public function addPost(Post $post): void
    {
        $this->posts->add($post);
    }

    /**
     * @return Collection
     */
    public function getProtagonists(): Collection
    {
        return $this->protagonists;
    }

    /**
     * @param Collection $protagonists
     */
    public function setProtagonists(Collection $protagonists): void
    {
        $this->protagonists = $protagonists;
    }

    /**
     * @param Protagonist $protagonist
     */
    public function addProtagonist(Protagonist $protagonist): void
    {
        $this->protagonists->add($protagonist);
    }

    /**
     * @return Collection
     */
    public function getSentMessages(): Collection
    {
        return $this->sentMessages;
    }

    /**
     * @param Collection $sentMessages
     */
    public function setSentMessages(Collection $sentMessages): void
    {
        $this->sentMessages = $sentMessages;
    }

    /**
     * @param Message $message
     */
    public function addSentMessage(Message $message): void
    {
        $this->sentMessages->add($message);
    }

    /**
     * @return Collection
     */
    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages;
    }

    /**
     * @param Collection $receivedMessages
     */
    public function setReceivedMessages(Collection $receivedMessages): void
    {
        $this->receivedMessages = $receivedMessages;
    }

    /**
     * @param Message $message
     */
    public function addReceivedMessage(Message $message): void
    {
        $this->receivedMessages->add($message);
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string|null $avatar
     */
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
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
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
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
