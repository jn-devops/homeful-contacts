<?php

namespace App\Console\Commands;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Console\Command;
use App\Imports\UsersImport;

class ImportUsers extends Command
{
    protected $signature = 'app:import-users';

    protected $description = 'Import from CSV to Users and Contacts';

    public function handle(string $path = 'app/private/contacts.csv')
    {
        if (!file_exists($storage_path = storage_path($path))) {
            $this->fail($storage_path . ' is missing.');
        }

        Excel::import($import = app( UsersImport::class), $storage_path);
    }
}
