<?php

namespace App\Listeners;

use App\Services\WishlistService;
use Illuminate\Auth\Events\Login;

class MergeGuestWishlistOnLogin
{
    public function __construct(private WishlistService $wishlist) {}

    public function handle(Login $event): void
    {
        $this->wishlist->mergeGuestToDb();
    }
}
