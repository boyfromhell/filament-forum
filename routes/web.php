<?php

use IchBin\FilamentForum\Models\Discussion;
use IchBin\FilamentForum\Models\Tag;
use Illuminate\Support\Facades\Route;

Route::prefix('/forum')
    ->name('forum.')
    ->middleware(['auth', 'web', 'role:super_admin'])
    ->group(function () {
        Route::view('/', 'filament-forum::forum-home')->name('index');
        Route::get('/{discussion}/{slug}', function (Discussion $discussion) {
            return view('filament-forum::discussion', compact('discussion'));
        })->name('discussion')->middleware(['web', 'discussion']);
        Route::view('/tags', 'filament-forum::forum-tags')
            ->name('tags');

        Route::get('/tag/{tag}/{slug}', function (Tag $tag) {
            return view('filament-forum::forum-tag', compact('tag'));
        })
            ->name('tag');

        Route::get('search', function () {
            if (! request('q')) {
                return redirect()->route('forum.index');
            }

            return view('filament-forum::forum-search');
        })
            ->name('search');
    });
