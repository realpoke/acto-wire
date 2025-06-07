<?php

namespace App\Actions\Auth\User;

use App\Actions\BaseAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutUserAction extends BaseAction
{
    protected function execute(Mixed ...$payload): static
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return $this->setSuccessful();
    }
}
