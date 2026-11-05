<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('articles:publish-scheduled')]
#[Description('Publikasikan artikel terjadwal yang sudah jatuh tempo')]
class PublishScheduledArticles extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $publishedCount = Article::query()
            ->where('status', 'Draft')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->update(['status' => 'Publish']);

        $this->info($publishedCount.' artikel terjadwal berhasil dipublikasikan.');

        return self::SUCCESS;
    }
}
