<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AuditLogs extends Component
{
    use WithPagination;

    public $user_id = '';

    public $action = '';

    public function updatedUserId()
    {
        $this->resetPage();
    }

    public function updatedAction()
    {
        $this->resetPage();
    }

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->when($this->user_id, function ($query) {
                $query->where('user_id', $this->user_id);
            })
            ->when($this->action, function ($query) {
                $query->where('action', $this->action);
            })
            ->latest()
            ->paginate(20);

        return view('livewire.audit-logs', [
            'logs' => $logs,
            'users' => User::all(),
        ]);
    }
}
