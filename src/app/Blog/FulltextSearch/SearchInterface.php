<?php
namespace App\Blog\FulltextSearch;

interface SearchInterface
{
    public function run($search);

    public function runForClass($search, $class);

    public function searchQuery($search);
}
