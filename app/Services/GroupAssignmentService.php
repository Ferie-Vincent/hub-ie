<?php

namespace App\Services;

use App\Models\Application;

class GroupAssignmentService
{
    private const GROUPS   = ['G1', 'G2', 'G3'];
    private const MAX_EACH = 60; // 180 / 3

    public function assign(Application $application): string
    {
        $counts = Application::where('status', 'accepted')
            ->whereNotNull('group_label')
            ->selectRaw('group_label, count(*) as total')
            ->groupBy('group_label')
            ->pluck('total', 'group_label')
            ->toArray();

        foreach (self::GROUPS as $group) {
            $filled = $counts[$group] ?? 0;
            if ($filled < self::MAX_EACH) {
                return $group;
            }
        }

        // All groups at cap — return the least-filled one
        $min   = PHP_INT_MAX;
        $best  = 'G1';
        foreach (self::GROUPS as $group) {
            $n = $counts[$group] ?? 0;
            if ($n < $min) {
                $min  = $n;
                $best = $group;
            }
        }

        return $best;
    }
}
