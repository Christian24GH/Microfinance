<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('email_logs')->insert([
            [
                'recipient_email' => 'cajetapatrick1@gmail.com',
                'subject' => 'mahal kita',
                'message' => '1',
                'status' => 'sent',
                'send_date' => '2025-05-20 04:40:57',
            ],
            [
                'recipient_email' => 'anaselportes22@gmail.com',
                'subject' => 'imissyou',
                'message' => '1',
                'status' => 'sent',
                'send_date' => '2025-05-20 04:45:12',
            ],
            [
                'recipient_email' => 'cajetapatrick1@gmail.com',
                'subject' => 'imissyou',
                'message' => '1',
                'status' => 'sent',
                'send_date' => '2025-05-20 04:45:16',
            ],
            [
                'recipient_email' => 'lawrencebarraza011404@gmail.com',
                'subject' => 'imissyou',
                'message' => '1',
                'status' => 'sent',
                'send_date' => '2025-05-20 04:45:19',
            ],
        ]);
    }
}
