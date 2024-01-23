<?php

namespace App\Jobs;

use App\Models\Product;
use Elastic\Elasticsearch\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteProductElasticJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Product $product)
    {
        $this->onQueue('delete-elastic');
    }

    /**
     * Execute the job.
     */
    public function handle(Client $client): void
    {
        try {
            $client->delete([
                'index' => 'products',
                'id' => $this->product->id,
            ]);
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }
}
