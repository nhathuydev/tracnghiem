<?php

namespace App\Providers;

use App\Repository\Answer\AnswerInterface;
use App\Repository\Answer\AnswerRepository;
use App\Repository\AnswerSheet\AnswerSheetInterface;
use App\Repository\AnswerSheet\AnswerSheetRepository;
use App\Repository\Collection\CollectionInterface;
use app\Repository\Collection\CollectionRepository;
use App\Repository\FeatureCollection\FeatureCollectionInterface;
use App\Repository\FeatureCollection\FeatureCollectionRepository;
use App\Repository\Notification\NotificationInterface;
use App\Repository\Notification\NotificationRepository;
use App\Repository\Question\QuestionInterface;
use App\Repository\Question\QuestionRepository;
use App\Repository\Tag\TagInterface;
use App\Repository\Tag\TagRepository;
use App\Repository\User\UserInterface;
use App\Repository\User\UserRepository;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\HttpKernel;

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

        $this->app->bind(
            CollectionInterface::class,
            CollectionRepository::class
        );

        $this->app->bind(
            UserInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            AnswerSheetInterface::class,
            AnswerSheetRepository::class
        );

        $this->app->bind(
            UserInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            FeatureCollectionInterface::class,
            FeatureCollectionRepository::class
        );

        $this->app->bind(
            NotificationInterface::class,
            NotificationRepository::class
        );
    }
}
