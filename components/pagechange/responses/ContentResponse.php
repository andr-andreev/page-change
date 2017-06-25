<?php
declare(strict_types=1);


namespace app\components\pagechange\responses;

use League\HTMLToMarkdown\HtmlConverter;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ContentResponse
 * @package app\components\pagechange\responses
 */
class ContentResponse extends AbstractResponse
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * ContentResponse constructor.
     * @param $response
     */
    public function __construct(\Psr\Http\Message\ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return null|string
     */
    public function getContent(): ?string
    {
        $body = (string)$this->response->getBody();
        $html = static::convertWebPageToUnicode($body);

        return static::convertHtmlToMarkdown($html);
    }

    /**
     * @param string $content
     * @return string
     */
    public static function convertWebPageToUnicode(string $content): string
    {
        // using DomCrawler component to convert document encoding to utf-8
        $crawler = new Crawler($content);
        return $crawler->html();
    }

    /**
     * @param string $html
     * @return string
     */
    public static function convertHtmlToMarkdown(string $html): string
    {
        $converter = new HtmlConverter();
        $config = $converter->getConfig();
        $config->setOption('strip_tags', true);
        $config->setOption('remove_nodes', 'script style');

        return $converter->convert($html);
    }

    /**
     * @return null|string
     */
    public function getStatusMessage(): ?string
    {
        return null;
    }
}
