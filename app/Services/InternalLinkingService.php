<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\InternalLinkingServiceInterface;
use App\Models\BlogPost;
use App\Models\InternalLink;
use App\Models\Page;
use Illuminate\Support\Collection;

class InternalLinkingService implements InternalLinkingServiceInterface
{
    public function buildLinksForPage(Page $page): Collection
    {
        $page->loadMissing(['city', 'service']);

        $links = collect();

        $sameCityPages = Page::query()
            ->published()
            ->where('city_id', $page->city_id)
            ->whereKeyNot($page->id)
            ->where('service_id', '!=', $page->service_id)
            ->take(4)
            ->get();

        foreach ($sameCityPages as $targetPage) {
            $links->push($this->upsertLink($page, $targetPage, $targetPage->service?->name ?? 'Service local', 'service_in_city'));
        }

        $nearbyCityCodes = collect($page->city?->nearby_cities ?? [])->pluck('code_insee')->all();
        $nearbyPages = Page::query()
            ->published()
            ->whereHas('city', fn ($query) => $query->whereIn('code_insee', $nearbyCityCodes))
            ->where('service_id', $page->service_id)
            ->take(4)
            ->get();

        foreach ($nearbyPages as $targetPage) {
            $links->push($this->upsertLink($page, $targetPage, ($page->service?->name ?? 'Service').' '.$targetPage->city?->name, 'service_in_nearby_city'));
        }

        $blogPosts = BlogPost::query()
            ->published()
            ->where('category', $page->service?->name)
            ->take(2)
            ->get();

        foreach ($blogPosts as $post) {
            $syntheticPage = new Page([
                'id' => $page->id,
                'slug' => 'blog/'.$post->slug,
            ]);

            $links->push($this->upsertLink($page, $syntheticPage, $post->title, 'blog', false));
        }

        $devisPage = Page::query()
            ->published()
            ->where('city_id', $page->city_id)
            ->where('service_id', $page->service_id)
            ->where('page_type', 'devis')
            ->first();

        if ($devisPage !== null) {
            $links->push($this->upsertLink($page, $devisPage, 'Demander un devis', 'devis'));
        }

        $urgencePage = Page::query()
            ->published()
            ->where('city_id', $page->city_id)
            ->where('service_id', $page->service_id)
            ->where('page_type', 'urgence')
            ->first();

        if ($urgencePage !== null) {
            $links->push($this->upsertLink($page, $urgencePage, 'Urgence '.$page->service?->name, 'urgence'));
        }

        return $links->filter()->take(10)->values();
    }

    public function rebuildAllLinks(): int
    {
        $count = 0;

        Page::query()->published()->chunkById(100, function ($pages) use (&$count): void {
            foreach ($pages as $page) {
                $count += $this->buildLinksForPage($page)->count();
            }
        });

        return $count;
    }

    private function upsertLink(Page $fromPage, Page $toPage, string $anchorText, string $type, bool $persist = true): ?InternalLink
    {
        if (! $persist || ! isset($toPage->id) || $toPage->id === null) {
            return new InternalLink([
                'from_page_id' => $fromPage->id,
                'to_page_id' => 0,
                'anchor_text' => $anchorText,
                'link_type' => $type,
                'is_active' => true,
            ]);
        }

        return InternalLink::query()->updateOrCreate(
            [
                'from_page_id' => $fromPage->id,
                'to_page_id' => $toPage->id,
            ],
            [
                'anchor_text' => $anchorText,
                'link_type' => $type,
                'is_active' => true,
            ]
        );
    }
}
