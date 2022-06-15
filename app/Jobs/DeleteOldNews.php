<?php

namespace App\Jobs;

use App\Models\News;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteOldNews implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 5400; // Set 90min timeout
    public $tries = 3; // If the job fails more than 3 times a manual investigation should be done

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $allNews = News::all();
            if(count($allNews) > 0){
                foreach($allNews as $news){
                    $created_at = date_create($news->created_at);
                    $current_date = date_create(date("Y-m-d"));
                    $diff=date_diff($created_at,$current_date);
                    $days =  $diff->format("%a");
                    if($days >= 14){
                        $news->delete(); 
                    }
                }
            }
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'error' => 'Error while deleting old News',
                'exception' => $e->getMessage(),
            ], 500);
        }    
    }
}
