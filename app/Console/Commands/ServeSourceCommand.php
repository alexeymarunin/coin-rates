<?php

namespace App\Console\Commands;

use App\Source;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ServeSourceCommand extends Command
{
    const TIMEOUT = 5;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'source:serve {name?}';

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
     * @return mixed
     */
    public function handle()
    {
        $sourceName = $this->argument( 'name' );

        $sources = $sourceName ? explode( ',', $sourceName ) : Source::all( 'name' )->map(function( $item ) {
            return $item['name'];
        })->toArray();

        while ( true ) {
            foreach ( $sources as $source ) {
                $result = Artisan::call( 'source:request', [ 'name' => $source ]);
            }
            $this->line( 'Waiting ' . static::TIMEOUT . ' sec...' );
            sleep( static::TIMEOUT );
        }
    }
}
