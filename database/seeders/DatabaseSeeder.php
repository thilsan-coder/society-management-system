<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Expense;
use App\Models\Fine;
use App\Models\Meeting;
use App\Models\Member;
use App\Models\PosterAndMedia;
use App\Models\Santha;
use App\Models\User;
use App\Services\FineService;
use App\Services\PaymentService;
use App\Services\SanthaService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users & Members
        $usersData = [
            [
                'name' => 'President Strategic Director',
                'email' => 'president@society.com',
                'phone' => '0771234567',
                'role' => 'president',
                'member_number' => 'MEM-001',
            ],
            [
                'name' => 'Secretary General',
                'email' => 'secretary@society.com',
                'phone' => '0772345678',
                'role' => 'secretary',
                'member_number' => 'MEM-002',
            ],
            [
                'name' => 'Treasurer Chief Financial Officer',
                'email' => 'treasurer@society.com',
                'phone' => '0773456789',
                'role' => 'treasurer',
                'member_number' => 'MEM-003',
            ],
            [
                'name' => 'Media & Communications Manager',
                'email' => 'media@society.com',
                'phone' => '0774567890',
                'role' => 'media',
                'member_number' => 'MEM-004',
            ],
            [
                'name' => 'Member Robert Smith',
                'email' => 'member1@society.com',
                'phone' => '0775678901',
                'role' => 'member',
                'member_number' => 'MEM-005',
            ],
            [
                'name' => 'Member Elena Rostova',
                'email' => 'member2@society.com',
                'phone' => '0776789012',
                'role' => 'member',
                'member_number' => 'MEM-006',
            ],
        ];

        $createdMembers = [];

        foreach ($usersData as $uData) {
            $user = User::create([
                'name' => $uData['name'],
                'email' => $uData['email'],
                'phone' => $uData['phone'],
                'role' => $uData['role'],
                'status' => 'active',
                'password' => Hash::make('password123'),
            ]);

            $member = Member::create([
                'user_id' => $user->id,
                'member_number' => $uData['member_number'],
                'join_date' => '2026-01-01',
                'address' => '123 Society Residency, Main Boulevard',
                'emergency_contact' => '0770000000',
                'created_by' => 1,
            ]);

            $createdMembers[$uData['member_number']] = $member;

            AuditLog::create([
                'user_id' => 1,
                'action' => "Seeded User {$user->name} ({$uData['member_number']})",
                'model_type' => User::class,
                'model_id' => $user->id,
                'created_at' => now(),
            ]);
        }

        // 2. Generate Santha records & evaluate cycles
        $santhaService = new SanthaService();
        $santhaService->generateMonthlySanthasForAllMembers();

        // 3. Create Sample Meetings
        $meeting1 = Meeting::create([
            'title' => 'Annual Strategy & Financial Meeting 2026',
            'meeting_date' => '2026-02-15',
            'start_time' => '18:00',
            'end_time' => '20:00',
            'venue' => 'Society Main Conference Hall',
            'meeting_notes' => 'Reviewed annual goals, monthly Santha rate Rs 300, and 3-month settlement cycles.',
            'discussed_topics' => "1. Santha collection status\n2. Event posters & media planning\n3. Rule compliance",
            'decisions_resolutions' => "Resolution 1: Strict enforcement of 3-month Santha settlement cycle.\nResolution 2: Attendance absence fine Rs 100.",
            'action_items' => 'Treasurer to issue receipts for all payments.',
            'status' => 'published',
            'created_by' => $createdMembers['MEM-002']->user_id,
        ]);

        // 4. Create Sample Fines
        $fineService = new FineService();
        $fineService->createSpotFine($createdMembers['MEM-005'], 'Late arrival to annual assembly', 50.00, $createdMembers['MEM-003']->user);
        $fineService->createDefaultFine($createdMembers['MEM-006'], 'Failure to attend assigned society duty', 100.00, 'Did not complete maintenance shift', $createdMembers['MEM-003']->user);
        $fineService->createAbsenceFine($createdMembers['MEM-005'], $meeting1, 100.00, $createdMembers['MEM-002']->user);

        // 5. Create Sample Payments
        $paymentService = new PaymentService();
        $member1Santha = Santha::where('member_id', $createdMembers['MEM-005']->id)->first();
        if ($member1Santha) {
            $paymentService->recordExplicitPayment(
                $createdMembers['MEM-005'],
                [$member1Santha->id => 300.00],
                [],
                'cash',
                'REF-1001',
                'Santha January payment',
                $createdMembers['MEM-003']->user
            );
        }

        // 6. Create Sample Expenses
        Expense::create([
            'category' => 'Main Hall Utilities & Electricity',
            'amount' => 450.00,
            'expense_date' => '2026-02-20',
            'description' => 'Monthly electricity bill for society hall',
            'payment_method' => 'bank_transfer',
            'reference_number' => 'BILL-9921',
            'recorded_by' => $createdMembers['MEM-003']->user_id,
        ]);

        // 7. Create Sample Media / Poster
        PosterAndMedia::create([
            'title' => 'Bonds of Friendship Grand Gala 2026',
            'media_type' => 'poster',
            'event_date' => '2026-10-15',
            'event_time' => '17:00',
            'venue' => 'Society Garden & Main Auditorium',
            'description' => 'Join us for our official annual fellowship gathering and cultural evening.',
            'status' => 'published',
            'submitted_by' => $createdMembers['MEM-004']->user_id,
            'reviewed_by' => $createdMembers['MEM-001']->user_id,
        ]);
    }
}
