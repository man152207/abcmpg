<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\DailyLog;

class DailyLogPolicy
{
    // Super Admin = admins.id === 1
    public function before(Admin $user, $ability) {
        return ($user->id === 1) ? true : null;
    }

    public function viewAny(Admin $user) { return true; }
    public function create(Admin $user) { return true; }

    public function view(Admin $user, DailyLog $log) {
        return $log->admin_id === $user->id;
    }
    public function update(Admin $user, DailyLog $log) {
        return $log->admin_id === $user->id;
    }
    public function delete(Admin $user, DailyLog $log) {
        return $log->admin_id === $user->id;
    }
}
