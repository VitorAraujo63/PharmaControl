<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    public function created(Model $model)
    {
        $this->log($model, 'CREATE', $model->toArray());
    }

    public function updated(Model $model)
    {
        $changes = $model->getChanges();
        unset($changes['updated_at']);

        if (! empty($changes)) {
            $this->log($model, 'UPDATE', $changes);
        }
    }

    public function deleted(Model $model)
    {
        $this->log($model, 'DELETE', $model->toArray());
    }

    private function log(Model $model, string $action, array $details)
    {
        if (! Auth::check()) {
            return;
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model' => class_basename($model),
            'model_id' => $model->getKey(),
            'details' => $details,
            'ip_address' => request()->ip(),
        ]);
    }
}
