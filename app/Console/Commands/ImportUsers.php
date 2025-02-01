<?php

namespace App\Console\Commands;

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

        $this->output->title('Starting import');
        $import = app(UsersImport::class);
        $import->withOutput($this->output)->import($storage_path);
        $this->output->success('Import successful');

        foreach ($import->failures() as $failure) {
            logger($failure->row());
        }
    }
}
