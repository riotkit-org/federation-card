<?php declare(strict_types=1);

namespace App\Mechanism\Service;

use Symfony\Component\Yaml\Yaml;
use TightenCo\Jigsaw\Parsers\FrontMatterParser;
use \Mni\FrontYAML\Parser as FrontYAMLParser;

/**
 * Activities, Badges and Organizations factory
 */
class CollectionFactory
{
    private string $searchPath;
    private array $parsed;

    public function __construct(private string $type,
                                private string $preferredLanguage = 'pl',
                                private string $fallbackLanguage = 'en')
    {
        $this->searchPath = realpath(dirname(__FILE__)) . '/../../../content/data/_' . $type;
    }

    /**
     * @throws \Exception
     */
    public function discover(): array
    {
        $files = scandir($this->searchPath);

        if ($files === false) {
            throw new \Exception('Cannot discover any file - "' . $this->searchPath . '" is not valid path');
        }

        foreach ($files as $file) {
            if (!str_ends_with($file, '.md')) {
                continue;
            }

            $this->parse(file_get_contents($this->searchPath . '/' . $file), $file);
        }

        return $this->parsed;
    }

    private function parse(string $content, string $filename)
    {
        $jigsawParser = new FrontMatterParser(new FrontYAMLParser());
        $parsed            = $jigsawParser->parse($content);
        $multipleLanguages = $this->splitByLanguages($parsed->content, $filename);
        $defaultLanguageContent = $this->pickLanguage($multipleLanguages);

        $this->parsed[] = array_merge($parsed->frontMatter, [
            'extends'                => '_layouts.pages/' . $this->type . '-single',
            'contentTranslated'      => $multipleLanguages,
            'content'                => $defaultLanguageContent,
            'defaultLanguageContent' => $defaultLanguageContent
        ]);
    }

    private function pickLanguage(array $languageVersions): string
    {
        if (isset($languageVersions[$this->preferredLanguage])) {
            return $languageVersions[$this->preferredLanguage];
        }

        if (isset($languageVersions[$this->fallbackLanguage])) {
            return $languageVersions[$this->fallbackLanguage];
        }

        reset($languageVersions);
        return current($languageVersions);
    }

    private function splitByLanguages(string $content, string $filename): array
    {
        $content = "---\n" . $content;
        $split = preg_split("/\-\-\-\s+?lang:/", $content);
        $byLanguages = [];

        foreach ($split as $languageVersion) {
            $asLines = explode("\n", $languageVersion);

            $lang = trim($asLines[0]);
            unset($asLines[0]);

            $byLanguages[$lang] = implode("\n", $asLines);
        }

        if (!isset($byLanguages[$this->preferredLanguage]) && !isset($byLanguages[$this->fallbackLanguage])) {
            throw new \Exception('"' . $filename . '" should have description defined in any of those languages: ' . $this->preferredLanguage . ', ' . $this->fallbackLanguage);
        }

        return $byLanguages;
    }
}
