<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CreateUserRootDefaultCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:root-default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for created a user root if exits';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //



        Artisan::call("db:seed --class=UserRootSeeder");


        $this->info("Registrando Usuario Root");

        return Command::SUCCESS;


    }
}
