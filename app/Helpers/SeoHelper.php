<?php

namespace App\Helpers;

use Nomanur\FilamentSeoPro\Models\SeoMeta;

class SeoHelper
{
    protected static ?SeoMeta $seo = null;

    /**
     * Get or initialize the dynamic SEO meta instance.
     */
    public static function getSeo(): SeoMeta
    {
        if (self::$seo === null) {
            self::$seo = new SeoMeta([
                'robots' => 'index, follow',
                'seo_score' => 0,
            ]);

            // Share the seo wrapper with the views globally so app.blade.php renders it
            view()->share('model', (object) ['seo' => self::$seo]);
        }

        return self::$seo;
    }

    /**
     * Set default SEO meta tags for a page.
     */
    public static function setDefault(
        string $title,
        ?string $description = null,
        array $keywords = [],
        ?string $image = null
    ): void {
        $seo = self::getSeo();
        $seo->title = $title;
        $seo->description = $description ?? config('app.description', '');
        $seo->keywords = implode(', ', $keywords);

        if ($image) {
            $seo->og_image = $image;
            $seo->twitter_image = $image;
        }

        $seo->og_title = $title;
        $seo->og_description = $description ?? config('app.description', '');
        $seo->twitter_title = $title;
        $seo->twitter_description = $description ?? config('app.description', '');
    }

    /**
     * Set SEO data for an article or blog post.
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

        $seo = self::getSeo();
        $seo->canonical_url = $url;
        $seo->schema_type = 'Article';
    }

    /**
     * Set SEO data for a product page.
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

        $seo = self::getSeo();
        $seo->canonical_url = $url;
        $seo->schema_type = 'Product';
    }

    /**
     * Add structured data (JSON-LD) for rich snippets.
     */
    public static function addStructuredData(array $data): string
    {
        return '<script type="application/ld+json">'.json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).'</script>';
    }

    /**
     * Generate Organization schema.
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
     * @param  array  $items  [['name' => 'Home', 'url' => '/'], ...]
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
     */
    public static function setRobots(bool $noIndex = false, bool $noFollow = false): void
    {
        $robots = [];
        $robots[] = $noIndex ? 'noindex' : 'index';
        $robots[] = $noFollow ? 'nofollow' : 'follow';

        $seo = self::getSeo();
        $seo->robots = implode(', ', $robots);
    }
}
