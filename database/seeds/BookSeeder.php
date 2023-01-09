<?php

use App\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $books = factory(Book::class, 5)->create();

        foreach ($books as $book) {
            $book->categories()->attach(rand(1, 10));
        }
    }
}
