<?php

declare(strict_types=1);

namespace App\Entity\Activity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class VisitActivity extends Activity
{
    /**
     * @ORM\Column(type="string", length=10)
     */
    private string $method;

    /**
     * @ORM\Column(type="text")
     */
    private string $url;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private string $ip;

    /**
     * @ORM\Column(type="integer")
     */
    private int $statusCode;

    public function __construct(
        string $method,
        string $url,
        int $statusCode,
        string $ip,
        ?User $user
    ) {
        parent::__construct($user);
        $this->method = $method;
        $this->url = $url;
        $this->ip = $ip;
        $this->statusCode = $statusCode;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }
}
