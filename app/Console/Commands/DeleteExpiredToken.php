<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteExpiredToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete-expired-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete User\'s Expired Token (aged more than a month)';

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
        DB::table('public.personal_access_tokens')
            ->where('created_at', '<', date('Y-m-d H:i:s', strtotime('first day of last month')))
            ->delete();
        $this->info('Deleted expired Token');
    }
}
