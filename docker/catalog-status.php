<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

echo Category::query()->count().':'.Product::query()->count().PHP_EOL;
