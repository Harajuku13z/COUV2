<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\SeoServiceInterface;
use App\Models\BlogPost;
use App\Models\Company;
use App\Models\Page;
use Spatie\Robots\RobotsTxt;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SeoService implements SeoServiceInterface
{
    public function generateSitemap(): void
    {
        $sitemap = Sitemap::create();

        $sitemap->add(
            Url::create(rtrim(config('app.url'), '/'))
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        Page::query()
            ->with(['city'])
            ->published()
            ->get()
            ->each(function (Page $page) use ($sitemap): void {
                $priority = match (true) {
                    $page->page_type === 'home' => 1.0,
                    $page->page_type === 'service' => 0.9,
                    $page->page_type === 'service_city' && (($page->city?->population ?? 0) > 10000) => 0.8,
                    $page->page_type === 'service_city' && (($page->city?->population ?? 0) > 1000) => 0.7,
                    $page->page_type === 'blog' => 0.6,
                    default => 0.5,
                };

                $sitemap->add(
                    Url::create($this->generateCanonicalUrl($page))
                        ->setPriority($priority)
                        ->setLastModificationDate($page->updated_at ?? now())
                );
            });

        BlogPost::query()
            ->published()
            ->get()
            ->each(function (BlogPost $post) use ($sitemap): void {
                $sitemap->add(
                    Url::create(rtrim(config('app.url'), '/').'/blog/'.$post->slug)
                        ->setPriority(0.6)
                        ->setLastModificationDate($post->updated_at ?? now())
                );
            });

        $sitemap->writeToDisk('public', 'sitemap.xml');
    }

    public function generateRobotsTxt(): string
    {
        return RobotsTxt::create()
            ->addUserAgent('*')
            ->addAllow('/')
            ->addDisallow('/admin/')
            ->addDisallow('/api/')
            ->addSitemap(rtrim(config('app.url'), '/').'/storage/sitemap.xml')
            ->generate();
    }

    public function generateCanonicalUrl(Page $page): string
    {
        return rtrim(config('app.url'), '/').'/'.$page->slug;
    }

    public function generateOpenGraphData(Page $page, Company $company): array
    {
        return [
            'title' => $page->content?->meta_title ?? $page->slug,
            'description' => $page->content?->meta_description ?? $company->offer_text,
            'url' => $this->generateCanonicalUrl($page),
            'image' => $company->logo_path,
            'site_name' => config('app.name'),
        ];
    }

    public function generateTwitterCardData(Page $page, Company $company): array
    {
        return [
            'card' => 'summary_large_image',
            'title' => $page->content?->meta_title ?? $page->slug,
            'description' => $page->content?->meta_description ?? $company->offer_text,
            'image' => $company->logo_path,
        ];
    }

    public function generateBreadcrumb(Page $page): array
    {
        return array_values(array_filter([
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Accueil',
                'item' => rtrim(config('app.url'), '/'),
            ],
            $page->service ? [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $page->service->name,
                'item' => rtrim(config('app.url'), '/').'/services/'.$page->service->slug,
            ] : null,
            $page->city ? [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $page->city->name,
                'item' => $this->generateCanonicalUrl($page),
            ] : null,
        ]));
    }
}
