<?php

namespace App\Providers;

use App\Repository\Answer\AnswerInterface;
use App\Repository\Answer\AnswerRepository;
use App\Repository\Question\QuestionInterface;
use App\Repository\Question\QuestionRepository;
use App\Repository\Tag\TagInterface;
use App\Repository\Tag\TagRepository;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            AnswerInterface::class,
            AnswerRepository::class
        );

        $this->app->bind(
            QuestionInterface::class,
            QuestionRepository::class
        );

        $this->app->bind(
            TagInterface::class,
            TagRepository::class
        );
    }
}
