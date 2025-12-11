<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

final class Pint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pint {--test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running Pint...');

        $this->info('Options: '.$this->option('test'));

        $comand = '.\\vendor\\bin\\pint';

        if ($this->option('test')) {
            $comand .= ' --test';
        }

        $result = shell_exec($comand);

        $this->info($result ?: 'Pint completed with no output.');

        return Command::SUCCESS;
    }
}
