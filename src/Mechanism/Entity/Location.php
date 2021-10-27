<?php declare(strict_types=1);

namespace App\Mechanism\Entity;

class Location extends FederationCardEntity
{
    /**
     * @var Organization[] $organizations
     */
    private array $organizations;

    public function applyRelation(FederationCardEntity $relation): void
    {
        if ($relation instanceof Organization) {
            $this->organizations[] = $relation;
        }
    }

    /**
     * @return Organization[]
     */
    public function getOrganizations(): array
    {
        return $this->organizations;
    }
}
