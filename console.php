<?php
use Illuminate\Support\Facades\Artisan;
Artisan::command('alhasm:check', function () {
    $this->info('AL HASM Laravel app is installed. Website: / | Admin: /admin');
})->purpose('Verify application registration');
