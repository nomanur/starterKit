<?php

namespace App\Helpers;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;

class SeoHelper
{
    /**
     * Set default SEO meta tags for a page.
     *
     * @param string $title
     * @param string|null $description
     * @param array $keywords
     * @param string|null $image
     * @return void
     */
    public static function setDefault(
        string $title,
        ?string $description = null,
        array $keywords = [],
        ?string $image = null
    ): void {
        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description ?? config('app.description', ''));
        SEOMeta::setKeywords($keywords);
        SEOMeta::addMeta('author', config('app.name'));
        
        if ($image) {
            OpenGraph::addProperty('image', $image);
            TwitterCard::addValue('image', $image);
        }
        
        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description ?? config('app.description', ''));
        OpenGraph::setUrl(url()->current());
        OpenGraph::setSiteName(config('app.name'));
        
        TwitterCard::setTitle($title);
        TwitterCard::setSite('@' . config('social.twitter_handle', 'laravel'));
    }

    /**
     * Set SEO data for an article or blog post.
     *
     * @param string $title
     * @param string $description
     * @param string $url
     * @param string|null $image
     * @param string|null $publishedTime
     * @param string|null $author
     * @return void
     */
    public static function setArticle(
        string $title,
        string $description,
        string $url,
        ?string $image = null,
        ?string $publishedTime = null,
        ?string $author = null
    ): void {
        self::setDefault($title, $description, [], $image);
        
        SEOMeta::setCanonical($url);
        
        OpenGraph::setType('article');
        OpenGraph::setUrl($url);
        
        if ($publishedTime) {
            OpenGraph::addProperty('published_time', $publishedTime);
        }
        
        if ($author) {
            OpenGraph::addProperty('author', $author);
        }
        
        if ($image) {
            OpenGraph::addProperty('image', $image);
            TwitterCard::addValue('image', $image);
        }
    }

    /**
     * Set SEO data for a product page.
     *
     * @param string $title
     * @param string $description
     * @param string $url
     * @param float $price
     * @param string $currency
     * @param string|null $image
     * @param string $availability
     * @return void
     */
    public static function setProduct(
        string $title,
        string $description,
        string $url,
        float $price,
        string $currency = 'USD',
        ?string $image = null,
        string $availability = 'in stock'
    ): void {
        self::setDefault($title, $description, [], $image);
        
        SEOMeta::setCanonical($url);
        
        OpenGraph::setType('product');
        OpenGraph::setUrl($url);
        OpenGraph::addProperty('price:amount', $price);
        OpenGraph::addProperty('price:currency', $currency);
        OpenGraph::addProperty('availability', "http://schema.org/{$availability}");
        
        if ($image) {
            OpenGraph::addProperty('image', $image);
        }
    }

    /**
     * Add structured data (JSON-LD) for rich snippets.
     *
     * @param array $data
     * @return string
     */
    public static function addStructuredData(array $data): string
    {
        return '<script type="application/ld+json">' . json_encode($data) . '</script>';
    }

    /**
     * Generate Organization schema.
     *
     * @param string $name
     * @param string $url
     * @param string|null $logo
     * @param string|null $description
     * @return string
     */
    public static function organizationSchema(
        string $name,
        string $url,
        ?string $logo = null,
        ?string $description = null
    ): string {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $name,
            'url' => $url,
        ];

        if ($logo) {
            $schema['logo'] = $logo;
        }

        if ($description) {
            $schema['description'] = $description;
        }

        return self::addStructuredData($schema);
    }

    /**
     * Generate BreadcrumbList schema.
     *
     * @param array $items [['name' => 'Home', 'url' => '/'], ...]
     * @return string
     */
    public static function breadcrumbSchema(array $items): string
    {
        $itemListElements = [];
        
        foreach ($items as $index => $item) {
            $itemListElements[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => url($item['url']),
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemListElements,
        ];

        return self::addStructuredData($schema);
    }

    /**
     * Check if a page should be indexed.
     *
     * @param bool $noIndex
     * @param bool $noFollow
     * @return void
     */
    public static function setRobots(bool $noIndex = false, bool $noFollow = false): void
    {
        $robots = [];
        
        if ($noIndex) {
            $robots[] = 'noindex';
        } else {
            $robots[] = 'index';
        }
        
        if ($noFollow) {
            $robots[] = 'nofollow';
        } else {
            $robots[] = 'follow';
        }
        
        SEOMeta::addMeta('robots', implode(', ', $robots));
    }
}
