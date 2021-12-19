<?php

namespace App\DTO\Email;

class EmailDTO
{
    /**
     * @var string|null
     */
    private ?string $period = null;

    /**
     * @var array|null
     */
    private ?array $date_range = null;

    /**
     * @return string|null
     */
    public function getPeriod(): ?string
    {
        return $this->period;
    }

    /**
     * @param string|null $period
     */
    public function setPeriod(?string $period): void
    {
        $this->period = $period;
    }

    /**
     * @return array|null
     */
    public function getDateRange(): ?array
    {
        return $this->date_range;
    }

    /**
     * @param array|null $date_range
     */
    public function setDateRange(?array $date_range): void
    {
        $this->date_range = $date_range;
    }
}