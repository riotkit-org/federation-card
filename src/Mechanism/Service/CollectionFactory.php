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
                                private string $fallbackLanguage = 'pl')
    {
        $this->searchPath = realpath(dirname(__FILE__)) . '/../../../content/_' . $type;
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

        $this->parsed[] = array_merge($parsed->frontMatter, [
            'extends'           => '_layouts.pages/' . $this->type . '-single',
            'contentTranslated' => $multipleLanguages,
            'content'           => $this->pickLanguage($multipleLanguages)
        ]);
    }

    private function pickLanguage(array $languageVersions): string
    {
        if (in_array($this->preferredLanguage, $languageVersions)) {
            return $languageVersions[$this->preferredLanguage];
        }

        if (in_array($this->fallbackLanguage, $languageVersions)) {
            return $languageVersions[$this->fallbackLanguage];
        }

        reset($languageVersions);
        return current($languageVersions);
    }

    private function splitByLanguages(string $content, string $filename): array
    {
        $split = preg_split("/^\-\-\-[^-]/", $content);
        $byLanguages = [];

        foreach ($split as $languageVersion) {
            $asLines = explode("\n", $languageVersion);

            if (!str_starts_with($asLines[0], 'lang:')) {
                throw new \Exception('Language version should be marked with e.g. "lang: pl" in "' . $filename . '"');
            }

            $lang = Yaml::parse($asLines[0])['lang'];
            unset($asLines[0]);

            $byLanguages[$lang] = implode("\n", $asLines);
        }

        return $byLanguages;
    }
}
