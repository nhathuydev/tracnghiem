<?php

namespace App\Jobs;

use App\Repository\AnswerSheet\AnswerSheetRepository;
use function GuzzleHttp\Psr7\str;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

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

            Redis::publish('quiz-app', json_encode(array(
                'type' => NOTIFICATION_ANSWERSHEET_EXPIRED,
                'data' => $answerSheetRepository,
                'to' => ['u.' . $this->answerSheet->user_id],
                'message' => 'Bài làm của bạn đã hết thời gian',
            )));
        }
    }

}
