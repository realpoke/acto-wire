<?php

namespace App\Livewire\Partials;

use App\Actions\Auth\User\LogoutUserAction;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class NavigationComponent extends Component
{
    public function logout(): void
    {
        $logoutAction = LogoutUserAction::run();

        if ($logoutAction->successful()) {
            Flux::toast(__('toast.logout.success'), variant: 'success');

            $this->redirect(route('landing.page'), navigate: true);
        }
    }

    #[Computed()]
    public function user(): ?User
    {
        return Auth::user();
    }
}
