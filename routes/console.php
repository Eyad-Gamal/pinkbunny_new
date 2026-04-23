<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('flash-sale:clean')->hourly();
