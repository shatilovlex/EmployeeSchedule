<?php

declare(strict_types=1);

namespace App\Model\Employee\Entity;

use JsonSerializable;

class TimeRange implements JsonSerializable
{
    private string $start;
    private string $end;

    /**
     * TimeRange constructor.
     * @param string $start
     * @param string $end
     */
    public function __construct(string $start, string $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return string
     */
    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * @return string
     */
    public function getEnd(): string
    {
        return $this->end;
    }


    public function jsonSerialize(): array
    {
        return [
            'start' => $this->start,
            'end' => $this->end,
        ];
    }
}
