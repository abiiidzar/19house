<?php

namespace App\Services\Reporting;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReportFilters
{
    public static function fromRequest(Request $request): array
    {
        $data = $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
            'channel' => ['nullable', Rule::in([ReportService::CHANNEL_ALL, ReportService::CHANNEL_ONLINE, ReportService::CHANNEL_POS])],
        ]);

        $start = isset($data['from']) ? CarbonImmutable::parse($data['from'])->startOfDay() : CarbonImmutable::now()->startOfMonth();
        $end = isset($data['to']) ? CarbonImmutable::parse($data['to'])->endOfDay() : CarbonImmutable::now()->endOfDay();

        if ($start->greaterThan($end)) {
            throw ValidationException::withMessages(['to' => 'End date must be on or after start date.']);
        }
        if ($start->diffInDays($end) > 366) {
            throw ValidationException::withMessages(['to' => 'Choose a date range of at most 366 days.']);
        }

        return [
            'start' => $start,
            'end' => $end,
            'from' => $start->toDateString(),
            'to' => $end->toDateString(),
            'channel' => $data['channel'] ?? ReportService::CHANNEL_ALL,
        ];
    }
}
