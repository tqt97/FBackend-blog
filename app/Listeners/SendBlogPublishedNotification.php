<?php

namespace App\Listeners;

use App\Models\NewsLetter;
use App\Mail\BlogPublished;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBlogPublishedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $subscribers = NewsLetter::subscribed()->get();

        foreach ($subscribers as $subscriber) {
            Mail::queue(new BlogPublished($event->post, $subscriber->email));
        }
    }
}
