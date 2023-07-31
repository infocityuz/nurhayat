<?php

namespace App\Console\Commands;

use Modules\ForTheBuilder\Entities\Notification_;
use Illuminate\Console\Command;

class AddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notification = new Notification_();
        $notification->data = json_encode(['sdsd' => 'sdsdsd']);
        $notification->notifiable_id = 1;
        $notification->type = 'BookingPrepayment';
        $notification->user_id = 15;
        $notification->save();
        
    }
}
