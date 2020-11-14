<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class DeactivateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:deactivateuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate User which are not paid monthly';

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
     * @return mixed
     */
    public function handle()
    {
        $users = User::with('payment')->whereHas('payment',function ($subquery){
            $subquery->where('payment_datetime', '<', date('Y-m-d', strtotime(date('Y-m-d') . ' -30 day')));
        })->get();

        $users->map(function($user_single){
            User::Where('id',$user_single->id)->update(['status'=>0]);
        });
    }
}
