<?php

namespace App\Listeners;

use App\Events\NotificationSent;
use App\Models\Notification;
use App\Models\UserRole;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class LogNotification
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
     * @param NotificationSent $event
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        DB::beginTransaction();
        try {
            Notification::create([
                'model' => @$event->data['model'],
                'model_id' => @$event->data['model_id'],
                'url' => @$event->data['url'],
                'title' => @$event->data['title'],
                'description' => @$event->data['description']
            ]);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
        }
    }
}
