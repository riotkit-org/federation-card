<?php declare(strict_types=1);

namespace App\Mechanism\Entity;

use TightenCo\Jigsaw\Collection\CollectionItem;

abstract class FederationCardEntity extends CollectionItem
{
    public abstract function applyRelation(FederationCardEntity $relation): void;

    public function getDescription(string $language = null): string
    {
        if ($language && isset($this->contentTranslated[$language]) && $this->contentTranslated[$language]) {
            return (string) $this->contentTranslated[$language];
        }

        return $this->defaultLanguageContent;
    }
}
