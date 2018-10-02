<?php
/*
 * This file is part of ImgCache.
 *
 * (c) Igor Lazarev <strider2038@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\OpenAPI;

use App\Mock\Parameters\MockParametersCollection;
use App\OpenAPI\Parsing\SpecificationParser;
use App\Utility\FileLoader;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class SpecificationLoader
{
    private const FORMAT_BY_EXTENSION_MAP = [
        'yaml' => 'yaml',
        'yml' => 'yaml',
        'json' => 'json',
    ];

    /** @var FileLoader */
    private $fileLoader;

    /** @var DecoderInterface */
    private $decoder;

    /** @var SpecificationParser */
    private $parser;

    public function __construct(FileLoader $fileLoader, DecoderInterface $decoder, SpecificationParser $parser)
    {
        $this->fileLoader = $fileLoader;
        $this->decoder = $decoder;
        $this->parser = $parser;
    }

    public function loadMockParameters(string $url): MockParametersCollection
    {
        $format = $this->guessFormatByExtension($url);
        $fileContents = $this->fileLoader->loadFileContents($url);
        $specification = $this->decoder->decode($fileContents, $format);

        return $this->parser->parseSpecification($specification);
    }

    private function guessFormatByExtension(string $url)
    {
        $extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));

        if (array_key_exists($extension, self::FORMAT_BY_EXTENSION_MAP)) {
            $format = self::FORMAT_BY_EXTENSION_MAP[$extension];
        } else {
            throw new \DomainException('Unsupported OpenAPI specification format. Supported formats: YAML and JSON.');
        }

        return $format;
    }
}