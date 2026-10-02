<?php

namespace Database\Seeders;

use App\Models\{User, VisitorLog};
use Illuminate\Database\Seeder;

class VisitorLogSeeder extends Seeder
{
    /**
     * Seeds 30 days of realistic visitor traffic for dashboard charts.
     */
    public function run(): void
    {
        $pages = [
            '/', '/products', '/products/bunny-blush-palette',
            '/products/glow-serum-pro', '/products/hydrating-cloud-cream',
            '/products/bond-repair-shampoo', '/products/velvet-body-lotion',
            '/brands', '/about', '/cart',
        ];

        $browsers  = ['Chrome', 'Safari', 'Firefox', 'Edge'];
        $platforms = ['Windows', 'iOS', 'Android', 'macOS'];
        $devices   = ['desktop', 'mobile', 'tablet'];
        $deviceWeights = [50, 40, 10]; // % desktop, mobile, tablet

        $users = User::all()->pluck('id')->toArray();

        $ips = array_map(fn($i) => '41.33.' . rand(1, 254) . '.' . rand(1, 254), range(1, 80));

        $inserted = 0;

        for ($day = 30; $day >= 0; $day--) {
            $date = now()->subDays($day)->toDateString();

            // Simulate traffic: weekends get more visitors
            $dayOfWeek   = now()->subDays($day)->dayOfWeek;
            $isWeekend   = in_array($dayOfWeek, [5, 6]); // Fri/Sat
            $visitorCount = $isWeekend ? rand(60, 120) : rand(30, 80);

            for ($v = 0; $v < $visitorCount; $v++) {
                $ip       = $ips[array_rand($ips)];
                $device   = $this->weightedRandom($devices, $deviceWeights);
                $platform = match($device) {
                    'mobile'  => $platforms[array_rand(['iOS', 'Android'])],
                    'tablet'  => 'iOS',
                    default   => $platforms[array_rand(['Windows', 'macOS'])],
                };

                // Each visitor hits 1-4 pages
                $pageCount = rand(1, 4);
                for ($p = 0; $p < $pageCount; $p++) {
                    VisitorLog::create([
                        'ip_address'   => $ip,
                        'user_agent'   => 'Mozilla/5.0 (' . $platform . ') AppleWebKit/537.36',
                        'page_url'     => 'http://localhost' . $pages[array_rand($pages)],
                        'referer'      => rand(0, 1) ? 'https://www.google.com' : null,
                        'browser'      => $browsers[array_rand($browsers)],
                        'platform'     => $platform,
                        'device_type'  => $device,
                        'user_id'      => rand(0, 3) === 0 && !empty($users) ? $users[array_rand($users)] : null,
                        'visited_date' => $date,
                        'created_at'   => now()->subDays($day)->setTime(rand(8, 23), rand(0, 59)),
                        'updated_at'   => now()->subDays($day),
                    ]);
                    $inserted++;
                }
            }
        }

        $this->command->info("✅ {$inserted} visitor log entries seeded (30 days).");
    }

    private function weightedRandom(array $items, array $weights): string
    {
        $rand = rand(1, array_sum($weights));
        $cumulative = 0;
        foreach ($items as $i => $item) {
            $cumulative += $weights[$i];
            if ($rand <= $cumulative) return $item;
        }
        return $items[0];
    }
}
