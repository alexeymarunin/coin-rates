<?php

namespace App\Console\Commands;

use App\Source;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RequestSourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'source:request {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Request valute data from specified source';

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
        $sourceName = $this->argument('name');

        $this->line( 'Request data from source ' . $sourceName );
        Source::where( 'name', $sourceName )->firstOrFail();

        $sourceClass = 'App\\' . ucfirst( $sourceName ) . 'Source';
        if ( !class_exists( $sourceClass ) ) {
            throw new ModelNotFoundException( 'This source does not supported: class ' . $sourceClass . ' not found' );
        }

        /** @var Source $source */
        $source = call_user_func( [ $sourceClass, 'where' ], 'name', $sourceName )->first();
        $this->line( 'Sending request to ' . $source->url );
        $time = time();
        $source->request();
        $this->line( 'Total ' . ( time() - $time ) . 'ms' );
    }
}
