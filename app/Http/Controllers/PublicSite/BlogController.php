<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Company;

class BlogController extends Controller
{
    public function index()
    {
        $company = Company::query()->first();
        $posts = BlogPost::query()
            ->published()
            ->latest('published_at')
            ->paginate(9);

        $seo = [
            'title' => 'Conseils et actualites | '.($company?->name ?? config('app.name')),
            'description' => $company?->offer_text ?? 'Articles conseils, alertes meteo et bonnes pratiques pour vos travaux.',
            'canonical' => url('/blog'),
            'type' => 'website',
            'image' => $company?->logo_path ? asset('storage/'.$company->logo_path) : null,
            'robots' => 'index,follow',
        ];

        $schema = [[
            '@context' => 'https://schema.org',
            '@type' => 'Blog',
            'name' => $seo['title'],
            'description' => $seo['description'],
            'url' => $seo['canonical'],
        ]];

        return view('public.blog-list', compact('company', 'posts', 'seo', 'schema'));
    }

    public function show(string $slug)
    {
        $company = Company::query()->first();
        $post = BlogPost::query()->published()->where('slug', $slug)->firstOrFail();
        $latestPosts = BlogPost::query()
            ->published()
            ->whereKeyNot($post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        $seo = [
            'title' => $post->meta_title,
            'description' => $post->meta_description,
            'canonical' => url('blog/'.$post->slug),
            'type' => 'article',
            'image' => $post->featured_image ? asset('storage/'.$post->featured_image) : ($company?->logo_path ? asset('storage/'.$company->logo_path) : null),
            'robots' => $post->status === 'published' ? 'index,follow' : 'noindex,nofollow',
        ];

        $breadcrumbs = [
            ['name' => 'Accueil', 'url' => url('/')],
            ['name' => 'Blog', 'url' => url('/blog')],
            ['name' => $post->title, 'url' => null],
        ];

        $schema = [[
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'description' => $post->meta_description,
            'datePublished' => optional($post->published_at)->toIso8601String(),
            'dateModified' => optional($post->updated_at)->toIso8601String(),
            'image' => $seo['image'],
            'mainEntityOfPage' => $seo['canonical'],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $company?->name ?? config('app.name'),
            ],
        ]];

        return view('public.blog-show', compact('company', 'post', 'latestPosts', 'seo', 'breadcrumbs', 'schema'));
    }
}
