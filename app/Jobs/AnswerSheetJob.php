<?php

namespace App\Jobs;

use App\Repository\AnswerSheet\AnswerSheetRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class AnswerSheetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    private $answerSheet, $answerSheetRepo;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($as)
    {
        $this->answerSheet = $as;
//        $this->answerSheetRepo = $answerSheetRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AnswerSheetRepository $answerSheetRepository)
    {
        if ($this->answerSheet->status === 0) {
            $answerSheetRepository->updateStatus($this->answerSheet->id, 1);
        }
    }
}
