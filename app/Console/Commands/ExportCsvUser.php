<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ExportCsvUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:export-csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export a CSV from User List';

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
        $fileName = 'user_export_' . time() . '.csv';
        $filePath = base_path('storage/app/public/') . $fileName;
        $file = fopen($filePath, 'w');
        fputcsv($file, array('id','name','email'));
        $users = User::all();
        foreach($users as $user){
            $row = array($user->id, $user->name, $user->email);
            fputcsv($file, $row);
        }
        fclose($file);
        $this->info('Exported User Table to CSV');
    }
}
