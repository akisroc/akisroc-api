<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
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
     * @var Collection|null
     */
    protected ?Collection $posts = null;

    /**
     * @ORM\OneToMany(targetEntity="Protagonist", mappedBy="user")
     *
     * @var Collection|null
     */
    protected ?Collection $protagonists = null;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="to")
     *
     * @var Collection|null
     */
    protected ?Collection $sentMessages = null;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="from")
     *
     * @var Collection|null
     */
    protected ?Collection $receivedMessages = null;

    /**
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
    public ?string $avatar = null;

    /**
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
    public ?string $username = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     *
     * @Assert\NotBlank(message="violation.email.blank")
     * @Assert\Email(message="violation.email.wrong_format")
     *
     * @var string|null
     */
    public ?string $email = null;

    /**
     * @ORM\Column(type="string", length=511, nullable=false)
     *
     * @var string|null
     */
    public ?string $password = null;

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
    public ?string $plainPassword = null;

    /**
     * @ORM\Column(type="string", length=127, nullable=false)
     *
     * @var string|null
     */
    public ?string $salt = null;

    /**
     * @ORM\Column(type="json", length=31, nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @var string[]
     */
    public array $roles = ['ROLE_USER'];

    /**
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Gedmo\Slug(fields={"username"})
     *
     * @var string|null
     */
    public ?string $slug = null;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    public bool $enabled = true;

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
     * @param Post $post
     * @return void
     */
    public function addPost(Post $post): void
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->author = $this;
        }
    }

    /**
     * @param Post $post
     * @return void
     */
    public function removePost(Post $post): void
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            if ($post->author === $this) {
                $post->author = null;
            }
        }
    }

    /**
     * @return Collection
     */
    public function getProtagonists(): Collection
    {
        return $this->protagonists;
    }

    /**
     * @param Protagonist $protagonist
     * @return void
     */
    public function addProtagonist(Protagonist $protagonist): void
    {
        if (!$this->protagonists->contains($protagonist)) {
            $this->protagonists[] = $protagonist;
            $protagonist->user = $this;
        }
    }

    public function removeProtagonist(Protagonist $protagonist): void
    {
        if ($this->protagonists->contains($protagonist)) {
            $this->protagonists->removeElement($protagonist);
            if ($protagonist->user === $this) {
                $protagonist->user = null;
            }
        }
    }

    /**
     * @return Collection
     */
    public function getSentMessages(): Collection
    {
        return $this->sentMessages;
    }

    /**
     * @param Message $sentMessage
     * @return void
     */
    public function addSentMessage(Message $sentMessage): void
    {
        if (!$this->sentMessages->contains($sentMessage)) {
            $this->sentMessages[] = $sentMessage;
            $sentMessage->from = $this;
        }
    }

    /**
     * @param Message $sentMessage
     * @return void
     */
    public function removeSentMessage(Message $sentMessage): void
    {
        if ($this->sentMessages->contains($sentMessage)) {
            $this->sentMessages->removeElement($sentMessage);
            if ($sentMessage->from === $this) {
                $sentMessage->from = null;
            }
        }
    }

    /**
     * @return Collection
     */
    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages;
    }

    /**
     * @param Message $receivedMessage
     * @return void
     */
    public function addReceivedMessage(Message $receivedMessage): void
    {
        if (!$this->receivedMessages->contains($receivedMessage)) {
            $this->receivedMessages[] = $receivedMessage;
            $receivedMessage->from = $this;
        }
    }

    /**
     * @param Message $receivedMessage
     * @return void
     */
    public function removeReceivedMessage(Message $receivedMessage): void
    {
        if ($this->receivedMessages->contains($receivedMessage)) {
            $this->receivedMessages->removeElement($receivedMessage);
            if ($receivedMessage->from === $this) {
                $receivedMessage->from = null;
            }
        }
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return $this->salt;
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
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('ROLE_ADMIN');
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
