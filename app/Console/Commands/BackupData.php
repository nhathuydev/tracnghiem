<?php

namespace App\Console\Commands;

use App\Models\AnswerSheet;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run task back up data (database + public folder)';

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
        $type = $this->choice('What do you want to back up?', ['all', 'database', 'assets'], '2');
        $this->info($type === 'assets');

        switch ($type) {
            case 'all': {
                $this->backupDatabase();
                $this->backupAsset();
                break;
            }
            case 'database': {
                $this->backupDatabase();
                break;
            }
            case 'assets': {
                $this->info('abcsfdsdaf');
                $this->backupAsset();
                break;
            }
        }
    }

    private function backupDatabase()
    {
        $u = $this->ask('Database\'s username');
        $filename = "backup-database-" . Carbon::now()->format('Y-m-d_H-i') . ".sql";
        $command = "mysqldump --user=" . $u ." -p --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  > " . storage_path() . "/" . $filename;
        $returnVar = NULL;
        $output  = NULL;
        exec($command, $output, $returnVar);
        info($returnVar);
    }
    private function backupAsset() {
        $filename = "backup-assets-" . Carbon::now()->format('Y-m-d_H-i') . ".tar.gz";
        $command = "tar -czvf " . storage_path($filename) . " public/";
        $output = null;

        exec($command, $output);
        foreach ($output as $li) {
            $this->info($li);
        }
    }
}
