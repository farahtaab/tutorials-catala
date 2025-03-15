<?php

use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ImportIfixitController;

Artisan::command('import:guides', function () {
    (new ImportIfixitController())->__invoke(request());
})->describe('Importa guías desde iFixit y las traduce al catalán');
