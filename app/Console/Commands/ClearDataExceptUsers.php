<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearDataExceptUsers extends Command
{
    protected $signature = 'db:clear-except-users';
    protected $description = 'Clear all database tables except users and related tables';

    public function handle()
    {
        if (!$this->confirm('This will delete all data except users. Continue?')) {
            $this->info('Operation cancelled.');
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $excludeTables = ['users', 'password_reset_tokens', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs', 'migrations'];
        
        $tables = DB::select('SHOW TABLES');
        $dbName = DB::getDatabaseName();
        $tableKey = "Tables_in_{$dbName}";

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            
            if (!in_array($tableName, $excludeTables)) {
                DB::table($tableName)->truncate();
                $this->info("Cleared: {$tableName}");
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info('All data cleared except users!');
    }
}
