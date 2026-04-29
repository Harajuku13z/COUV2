<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Page;
use Illuminate\Support\Collection;

interface InternalLinkingServiceInterface
{
    public function buildLinksForPage(Page $page): Collection;

    public function rebuildAllLinks(): int;
}
