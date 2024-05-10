<?php

use Carbon\Carbon;
use Illuminate\Support\Str;

if (!function_exists('diffDate')) {
    function diffDate($startDate, $endDate): int
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        return (int) $endDate->diffInDays($startDate);
    }
}

if (!function_exists('generatePathFile')) {
    function generatePathFile($path, $id, $extension): string
    {
        return $path . '/' . $id . '/' . $id . '-' . time() . Str::random(5) . '.' . $extension;
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        return number_format($amount, 0, ',', '.') . ' đ';
    }
}
