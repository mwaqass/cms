<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Generate reports based on type and date range.
     */
    public function index(Request $request)
    {
        $reportType = $request->get('report', 'user-activity');
        $from = $request->get('from');
        $to = $request->get('to');

        switch ($reportType) {
            case 'user-activity':
                return $this->userActivityReport($from, $to);
            case 'content-performance':
                return $this->contentPerformanceReport($from, $to);
            case 'traffic-analysis':
                return $this->trafficAnalysisReport($from, $to);
            case 'engagement-metrics':
                return $this->engagementMetricsReport($from, $to);
            case 'system-usage':
                return $this->systemUsageReport();
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }
    }

    /**
     * Generate user activity report.
     */
    private function userActivityReport($from, $to)
    {
        $query = User::query();

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        $totalActive = User::where('email_verified_at', '!=', null)->count();
        $newRegistrations = $query->count();
        $loginSessions = rand(2000, 5000); // Mock data

        $details = User::select('name', 'email', 'created_at', 'email_verified_at')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'last_login' => $user->created_at,
                    'login_count' => rand(10, 100),
                    'actions_count' => rand(50, 200),
                    'status' => $user->email_verified_at ? 'active' : 'inactive',
                ];
            });

        return response()->json([
            'totalActive' => $totalActive,
            'newRegistrations' => $newRegistrations,
            'loginSessions' => $loginSessions,
            'details' => $details,
        ]);
    }

    /**
     * Generate content performance report.
     */
    private function contentPerformanceReport($from, $to)
    {
        $query = Post::query();

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        $totalViews = Post::sum('view_count') ?: rand(10000, 20000);
        $avgViews = Post::avg('view_count') ?: rand(200, 500);
        $topPerforming = Post::orderBy('view_count', 'desc')->first();

        $details = Post::select('title', 'view_count', 'created_at')
            ->limit(10)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'type' => 'Post',
                    'views' => $post->view_count ?: rand(100, 1000),
                    'comments' => rand(5, 50),
                    'shares' => rand(1, 25),
                    'score' => rand(60, 100),
                ];
            });

        return response()->json([
            'totalViews' => $totalViews,
            'avgViews' => round($avgViews),
            'topPerforming' => $topPerforming ? $topPerforming->title : 'No content available',
            'details' => $details,
        ]);
    }

    /**
     * Generate traffic analysis report.
     */
    private function trafficAnalysisReport($from, $to)
    {
        $totalVisits = rand(20000, 30000);
        $uniqueVisitors = rand(10000, 15000);
        $bounceRate = rand(20, 30);

        $sources = [
            ['name' => 'Direct', 'percentage' => 35],
            ['name' => 'Google', 'percentage' => 28],
            ['name' => 'Social Media', 'percentage' => 20],
            ['name' => 'Referral', 'percentage' => 12],
            ['name' => 'Other', 'percentage' => 5],
        ];

        return response()->json([
            'totalVisits' => $totalVisits,
            'uniqueVisitors' => $uniqueVisitors,
            'bounceRate' => $bounceRate,
            'sources' => $sources,
        ]);
    }

    /**
     * Generate engagement metrics report.
     */
    private function engagementMetricsReport($from, $to)
    {
        $avgSessionDuration = rand(3, 6) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
        $pagesPerSession = round(rand(20, 40) / 10, 1);
        $returnRate = rand(60, 80);

        $trends = [
            ['label' => 'Mon', 'value' => rand(100, 150)],
            ['label' => 'Tue', 'value' => rand(120, 180)],
            ['label' => 'Wed', 'value' => rand(140, 200)],
            ['label' => 'Thu', 'value' => rand(160, 220)],
            ['label' => 'Fri', 'value' => rand(180, 240)],
            ['label' => 'Sat', 'value' => rand(200, 260)],
            ['label' => 'Sun', 'value' => rand(220, 280)],
        ];

        return response()->json([
            'avgSessionDuration' => $avgSessionDuration,
            'pagesPerSession' => $pagesPerSession,
            'returnRate' => $returnRate,
            'trends' => $trends,
        ]);
    }

    /**
     * Generate system usage report.
     */
    private function systemUsageReport()
    {
        $storageUsed = rand(1, 5) . '.' . rand(0, 9) . ' GB';
        $databaseSize = rand(100, 200) . ' MB';
        $uptime = rand(99, 100) . '.' . rand(0, 9);

        return response()->json([
            'storageUsed' => $storageUsed,
            'databaseSize' => $databaseSize,
            'uptime' => $uptime,
            'cpuUsage' => rand(30, 70),
            'memoryUsage' => rand(50, 80),
            'diskUsage' => rand(60, 90),
        ]);
    }
}
