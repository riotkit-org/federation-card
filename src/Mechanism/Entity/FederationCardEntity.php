<?php declare(strict_types=1);

namespace App\Mechanism\Entity;

use TightenCo\Jigsaw\Collection\CollectionItem;

abstract class FederationCardEntity extends CollectionItem
{
    public abstract function applyRelation(FederationCardEntity $relation): void;
}
