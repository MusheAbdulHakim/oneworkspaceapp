<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Internalknowledge\Entities\Book;
use Modules\Internalknowledge\Events\CreateArticle;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateArticleLis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreateArticle $event)
    {
        if (module_is_active('PabblyConnect')) {
            $article = $event->article;

            $book = Book::find($article->book);

            $pabbly_array = [
                'Book Title' => $book->title,
                'Article Title' => $article->title,
                'Article Description' => $article->description,
                'Article Type' => $article->type,
                'Article Content' => strip_tags($article->content)
            ];

            $action = 'New Artical';
            $module = 'Internalknowledge';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
