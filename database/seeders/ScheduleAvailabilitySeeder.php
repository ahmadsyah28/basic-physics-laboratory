<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ScheduleAvailability;
use Carbon\Carbon;

class ScheduleAvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh: Blokir beberapa slot untuk bulan ini sebagai demo
        $currentMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Default time slots (Senin-Jumat, 08:00-18:00 WIB)
        $timeSlots = ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00', '17:00'];

        // Contoh data: Blokir beberapa slot untuk maintenance
        $blockedDates = [
            [
                'date' => $currentMonth->copy()->addDays(5)->format('Y-m-d'),
                'slots' => ['08:00', '09:00'],
                'reason' => 'Maintenance peralatan lab',
                'notes' => 'Maintenance rutin peralatan laboratorium setiap bulan'
            ],
            [
                'date' => $currentMonth->copy()->addDays(15)->format('Y-m-d'),
                'slots' => ['13:00', '14:00', '15:00'],
                'reason' => 'Rapat koordinasi staff',
                'notes' => 'Rapat bulanan koordinasi staff laboratorium'
            ],
            [
                'date' => $currentMonth->copy()->addDays(25)->format('Y-m-d'),
                'slots' => ['10:00', '11:00'],
                'reason' => 'Training keselamatan lab',
                'notes' => 'Pelatihan keselamatan kerja di laboratorium untuk staff baru'
            ]
        ];

        // Insert blocked slots
                    foreach ($blockedDates as $blockedDate) {
            $date = $blockedDate['date'];

            // Skip if date is weekend (Saturday or Sunday)
            if (Carbon::parse($date)->isWeekend()) {
                continue;
            }

            foreach ($blockedDate['slots'] as $timeSlot) {
                ScheduleAvailability::create([
                    'date' => $date,
                    'time_slot' => $timeSlot,
                    'is_available' => false,
                    'reason' => $blockedDate['reason'],
                    'notes' => $blockedDate['notes']
                ]);
            }
        }

        // Contoh: Set beberapa slot yang explicitly available (optional)
        $availableDates = [
            [
                'date' => $currentMonth->copy()->addDays(10)->format('Y-m-d'),
                'slots' => $timeSlots, // Semua slot tersedia
                'reason' => null,
                'notes' => 'Slot tambahan dibuka untuk kunjungan khusus'
            ]
        ];

        foreach ($availableDates as $availableDate) {
            $date = $availableDate['date'];

            // Skip if date is weekend
            if (Carbon::parse($date)->isWeekend()) {
                continue;
            }

            foreach ($availableDate['slots'] as $timeSlot) {
                ScheduleAvailability::create([
                    'date' => $date,
                    'time_slot' => $timeSlot,
                    'is_available' => true,
                    'reason' => $availableDate['reason'],
                    'notes' => $availableDate['notes']
                ]);
            }
        }

        $this->command->info('Schedule availability seeder completed successfully!');
        $this->command->info('Created blocked slots for maintenance and meetings');
        $this->command->info('Created additional available slots for special visits');
    }
}
