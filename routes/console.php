<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('alerts:send-expiration')->weeklyOn(1, '08:00');
