<?php

use App\Livewire\Nodes;
use Illuminate\Support\Facades\Route;

Route::get('/', Nodes::class)->name('home');
