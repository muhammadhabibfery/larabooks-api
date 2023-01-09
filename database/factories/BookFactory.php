<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    $title = $faker->words(rand(2, 3), true);

    return [
        'city_id' => rand(1, 100),
        'title' => $title,
        'slug' => Str::slug($title),
        'description' => $faker->paragraph(rand(2, 3)),
        'author' => $faker->name('male'),
        'publisher' => $faker->company,
        'price' => (int) rand(250000, 550000),
        'weight' => mt_rand(350, 750),
        'stock' => mt_rand(1, 5),
        'status' => $faker->randomElement(['PUBLISH', 'DRAFT']),
        'created_by' => rand(1, 2)
    ];
});
