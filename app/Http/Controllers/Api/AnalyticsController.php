<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display analytics data.
     */
    public function index(Request $request)
    {
        $days = $request->get('days', 30);
        $startDate = now()->subDays($days);

        // Get user statistics
        $totalUsers = User::count();
        $newUsers = User::where('created_at', '>=', $startDate)->count();
        $userGrowth = $this->calculateGrowth('users', $days);

        // Get content statistics
        $totalPosts = Post::count();
        $newPosts = Post::where('created_at', '>=', $startDate)->count();
        $postGrowth = $this->calculateGrowth('posts', $days);

        // Get page views (mock data for now)
        $pageViews = rand(10000, 20000);
        $viewGrowth = rand(5, 25);

        // Get comments (mock data for now)
        $totalComments = rand(300, 500);
        $commentGrowth = rand(2, 15);

        return response()->json([
            'metrics' => [
                'totalUsers' => $totalUsers,
                'userGrowth' => $userGrowth,
                'totalPosts' => $totalPosts,
                'postGrowth' => $postGrowth,
                'pageViews' => $pageViews,
                'viewGrowth' => $viewGrowth,
                'totalComments' => $totalComments,
                'commentGrowth' => $commentGrowth,
            ],
            'topContent' => $this->getTopContent(),
            'trafficSources' => $this->getTrafficSources(),
            'recentActivity' => $this->getRecentActivity(),
        ]);
    }

    /**
     * Calculate growth percentage for a given table.
     */
    private function calculateGrowth($table, $days)
    {
        $currentPeriod = DB::table($table)
            ->where('created_at', '>=', now()->subDays($days))
            ->count();

        $previousPeriod = DB::table($table)
            ->whereBetween('created_at', [
                now()->subDays($days * 2),
                now()->subDays($days)
            ])
            ->count();

        if ($previousPeriod == 0) {
            return $currentPeriod > 0 ? 100 : 0;
        }

        return round((($currentPeriod - $previousPeriod) / $previousPeriod) * 100, 1);
    }

    /**
     * Get top performing content.
     */
    private function getTopContent()
    {
        return Post::select('title', 'view_count', 'id')
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'views' => $post->view_count ?? rand(100, 1000),
                    'comments' => rand(10, 50),
                ];
            });
    }

    /**
     * Get traffic sources (mock data).
     */
    private function getTrafficSources()
    {
        return [
            ['name' => 'Direct', 'percentage' => 35],
            ['name' => 'Google', 'percentage' => 28],
            ['name' => 'Social Media', 'percentage' => 20],
            ['name' => 'Referral', 'percentage' => 12],
            ['name' => 'Other', 'percentage' => 5],
        ];
    }

    /**
     * Get recent activity (mock data).
     */
    private function getRecentActivity()
    {
        return [
            [
                'id' => 1,
                'icon' => '👤',
                'text' => 'New user registered: john.doe@example.com',
                'created_at' => now()->subMinutes(5)
            ],
            [
                'id' => 2,
                'icon' => '📝',
                'text' => 'New post published: "Advanced Vue.js Techniques"',
                'created_at' => now()->subMinutes(15)
            ],
            [
                'id' => 3,
                'icon' => '💬',
                'text' => 'New comment on "Laravel Best Practices"',
                'created_at' => now()->subMinutes(30)
            ],
            [
                'id' => 4,
                'icon' => '👤',
                'text' => 'User profile updated: jane.smith@example.com',
                'created_at' => now()->subMinutes(45)
            ],
            [
                'id' => 5,
                'icon' => '📄',
                'text' => 'Page "About Us" was updated',
                'created_at' => now()->subMinutes(60)
            ],
        ];
    }
}
