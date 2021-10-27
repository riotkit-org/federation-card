<?php declare(strict_types=1);

namespace App\Mechanism\Entity;

use Exception;

class Organization extends FederationCardEntity
{
    /**
     * @var Activity[] $activity
     */
    private array $activities;

    private Location $location;

    /**
     * @var Badge[] $badges
     */
    private array $badges;

    /**
     * @param FederationCardEntity $relation
     *
     * @throws Exception
     */
    public function applyRelation(FederationCardEntity $relation): void
    {
        if ($relation instanceof Badge) {
            $this->badges[] = $relation;
        }

        elseif ($relation instanceof Location) {
            $this->location = $relation;
        }

        elseif ($relation instanceof Activity) {
            $this->activities[] = $relation;
        }

        else {
            throw new Exception('Unknown relation "' . get_class($relation) . '" for "' . get_class($this) . '"');
        }
    }

    /**
     * @return Activity[]
     */
    public function getActivities(): array
    {
        return $this->activities;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @return Badge[]
     */
    public function getBadges(): array
    {
        return $this->badges;
    }
}
