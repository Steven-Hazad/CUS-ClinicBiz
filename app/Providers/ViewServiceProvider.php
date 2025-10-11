<?php

     namespace App\Providers;

     use Illuminate\Support\Facades\View;
     use Illuminate\Support\ServiceProvider;

     class ViewServiceProvider extends ServiceProvider
     {
         public function boot()
         {
             // Register the 'mail' namespace for email templates
             View::addNamespace('mail', resource_path('views/emails'));
         }
     }
     