<?php

$root = dirname(__DIR__);
$database = $root.'/storage/framework/testing/catalog-preview.sqlite';
putenv('APP_ENV=testing');
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE='.$database);
putenv('DB_URL=');
putenv('CACHE_STORE=array');
putenv('SESSION_DRIVER=file');

if (PHP_SAPI === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($path !== '/' && is_file($root.'/public'.$path)) {
        return false;
    }
    require $root.'/public/index.php';
    return;
}

require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
if (!file_exists($database)) {
    touch($database);
}
Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => Database\Seeders\ColorSeeder::class, '--force' => true]);
foreach (range(1, 12) as $number) {
    $product = App\Models\Product::firstOrCreate(['name' => 'Filter fixture '.$number], [
        'desc' => 'Preview catalog item', 'price' => $number * 10, 'original_price' => $number * 10,
        'int_price' => 5, 'quantity' => 10,
    ]);
    $product->images()->firstOrCreate(['path' => 'assets/images/products/product_'.($number % 10).'.jpg'], [
        'color_id' => App\Models\Color::where('name', $number % 2 ? 'Blue' : 'Red')->value('id'),
        'product_id' => $product->id,
    ]);
}
echo 'Isolated catalog fixtures ready.'.PHP_EOL;
