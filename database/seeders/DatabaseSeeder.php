<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\TrackableItem;
use App\Models\TrackLogs;
use App\Models\ItemAchievement;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $user = User::first(); // 預設拿第一個使用者

        $types = [
            '工作', '興趣', '語言'
        ];

        foreach ($types as $typeName) {
            $type = Type::create([
                'user_id' => $user->id,
                'name' => $typeName,
            ]);

            $item = TrackableItem::create([
                'user_id' => $user->id,
                'type_id' => $type->id,
                'name' => $typeName . '測試項目',
                'exp' => 0,
                'level' => 1,
                'streak_days_required' => 5,
                'streak_bonus_exp' => 50,
                'achievement_text' => '連續 5 天完成 ' . $typeName . '!',
            ]);

            for ($i = 0; $i < 3; $i++) {
                TrackLogs::create([
                    'user_id' => $user->id,
                    'trackable_item_id' => $item->id,
                    'content' => $typeName . ' 第 ' . ($i + 1) . ' 天紀錄',
                    'exp_gained' => 10,
                    'created_at' => now()->subDays(3 - $i),
                ]);
            }
        }
    }
}
