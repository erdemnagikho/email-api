<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\CreatedAtTrait;

/**
 * @ORM\Table("emails", indexes={
 *     @ORM\Index(name="email_created_at_idx", columns={"created_at"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\EmailRepository")
 */
class Email
{
    use CreatedAtTrait;

    const CACHE_LIFETIME_ALL = 60 * 60 * 5;

    const CACHE_TAG_EMAILS = 'emails';

    const CACHE_KEY_LIST = 'emails-list-';

    const DAILY_PERIOD = 'daily';
    const WEEKLY_PERIOD = 'weekly';
    const MONTHLY_PERIOD = 'monthly';
    const YEARLY_PERIOD = 'yearly';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
