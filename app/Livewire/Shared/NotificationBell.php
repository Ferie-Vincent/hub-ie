<?php

declare(strict_types=1);

namespace App\Livewire\Shared;

use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class NotificationBell extends Component
{
    public int $userId;

    public bool $open = false;

    public function mount(): void
    {
        $this->userId = auth()->id();
    }

    public function getListeners(): array
    {
        return [
            "echo-private:App.Models.User.{$this->userId},Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'refresh',
        ];
    }

    #[Computed]
    public function unreadCount(): int
    {
        return auth()->user()->unreadNotifications()->count();
    }

    #[Computed]
    public function notifications(): Collection
    {
        return auth()->user()->notifications()->latest()->limit(8)->get();
    }

    public function markAllRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function markRead(string $id): void
    {
        auth()->user()->notifications()->find($id)?->markAsRead();
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function refresh(): void
    {
        unset($this->unreadCount, $this->notifications);
    }

    public function render()
    {
        return view('livewire.shared.notification-bell');
    }
}
