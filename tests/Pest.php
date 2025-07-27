<?php

pest()->extends(\Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in(
        'Feature',
        'Unit'
    );
