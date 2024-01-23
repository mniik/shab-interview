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

class UpdateProductElasticJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Product $product)
    {
        //        $this->onQueue('update-elastic');
    }

    /**
     * Execute the job.
     */
    public function handle(Client $client): void
    {
        try {
            $params = [
                'index' => 'products',
                'id' => $this->product->id,
                'body' => [
                    'title' => $this->product->title,
                    'price' => $this->product->price,
                ],
            ];

            $client->index($params);
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }
}
