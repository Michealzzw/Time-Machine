<?php

use Illuminate\Database\Seeder;
use App\Event_type;
class eventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('event_types')->delete();
      $tp1 = Event_type::create([
        'name' => '学习'
        ]);

        $tp2 = Event_type::create([
          'name' => '上课',
          'parent_event_type_id' => $tp1->id
          ]);

          $tp3 = Event_type::create([
            'name' => '专业课',
            'parent_event_type_id' => $tp2->id
            ]);

          $tp3 = Event_type::create([
            'name' => '英语课',
            'parent_event_type_id' => $tp2->id
            ]);

          $tp3 = Event_type::create([
            'name' => '政治课',
            'parent_event_type_id' => $tp2->id
            ]);

          $tp3 = Event_type::create([
            'name' => '通选课',
            'parent_event_type_id' => $tp2->id
            ]);

        $tp2 = Event_type::create([
          'name' => '自习',
          'parent_event_type_id' => $tp1->id
          ]);

          $tp3 = Event_type::create([
            'name' => '英语',
            'parent_event_type_id' => $tp2->id
            ]);

            $tp4 = Event_type::create([
              'name' => 'GRE',
              'parent_event_type_id' => $tp3->id
              ]);

              $tp5 = Event_type::create([
                'name' => '背单词',
                'parent_event_type_id' => $tp4->id
                ]);

              $tp5 = Event_type::create([
                'name' => '写作文',
                'parent_event_type_id' => $tp4->id
                ]);

              $tp5 = Event_type::create([
                'name' => '做阅读',
                'parent_event_type_id' => $tp4->id
                ]);

              $tp5 = Event_type::create([
                'name' => '练听力',
                'parent_event_type_id' => $tp4->id
                ]);

          $tp4 = Event_type::create([
            'name' => 'Toefl',
            'parent_event_type_id' => $tp3->id
            ]);

            $tp5 = Event_type::create([
              'name' => '背单词',
              'parent_event_type_id' => $tp4->id
              ]);

            $tp5 = Event_type::create([
              'name' => '写作文',
              'parent_event_type_id' => $tp4->id
              ]);

            $tp5 = Event_type::create([
              'name' => '做阅读',
              'parent_event_type_id' => $tp4->id
              ]);

            $tp5 = Event_type::create([
              'name' => '练听力',
              'parent_event_type_id' => $tp4->id
              ]);

          $tp3 = Event_type::create([
            'name' => '写代码',
            'parent_event_type_id' => $tp2->id
            ]);





      $tp1 = Event_type::create([
        'name' => '娱乐'
        ]);

          $tp2 = Event_type::create([
            'name' => '户外',
            'parent_event_type_id' => $tp1->id
            ]);

              $tp3 = Event_type::create([
                'name' => '网球',
                'parent_event_type_id' => $tp2->id
                ]);

              $tp3 = Event_type::create([
                'name' => '乒乓球',
                'parent_event_type_id' => $tp2->id
                ]);

              $tp3 = Event_type::create([
                'name' => '足球',
                'parent_event_type_id' => $tp2->id
                ]);

              $tp3 = Event_type::create([
                'name' => '篮球',
                'parent_event_type_id' => $tp2->id
                ]);

              $tp3 = Event_type::create([
                'name' => '羽毛球',
                'parent_event_type_id' => $tp2->id
                ]);

              $tp3 = Event_type::create([
                'name' => '登山',
                'parent_event_type_id' => $tp2->id
                ]);

              $tp3 = Event_type::create([
                'name' => '野营',
                'parent_event_type_id' => $tp2->id
                ]);

          $tp2 = Event_type::create([
            'name' => '室内',
            'parent_event_type_id' => $tp1->id
            ]);

              $tp3 = Event_type::create([
                'name' => '派对',
                'parent_event_type_id' => $tp2->id
                ]);

              $tp3 = Event_type::create([
                'name' => '唱K',
                'parent_event_type_id' => $tp2->id
                ]);

              $tp3 = Event_type::create([
                'name' => '打桌游',
                'parent_event_type_id' => $tp2->id
                ]);

              $tp3 = Event_type::create([
                'name' => '玩游戏',
                'parent_event_type_id' => $tp2->id
                ]);

                  $tp4 = Event_type::create([
                    'name' => '英雄联盟',
                    'parent_event_type_id' => $tp3->id
                    ]);

                  $tp4 = Event_type::create([
                    'name' => 'Dota2',
                    'parent_event_type_id' => $tp3->id
                    ]);

                  $tp4 = Event_type::create([
                    'name' => '星际争霸II',
                    'parent_event_type_id' => $tp3->id
                    ]);

    }
}
