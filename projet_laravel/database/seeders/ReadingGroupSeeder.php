<?php

namespace Database\Seeders;

use App\Models\ReadingGroup;
use App\Models\GroupMembership;
use App\Models\GroupEvent;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReadingGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding Reading Groups...');

        // Get all users
        $users = User::all();
        
        if ($users->count() < 3) {
            $this->command->warn('Not enough users. Creating additional users...');
            $users = User::factory()->count(10)->create();
        }

        // Create 15 reading groups with varied configurations
        $groups = ReadingGroup::factory()->count(15)->create()->each(function ($group) use ($users) {
            // Add the owner as a member
            GroupMembership::create([
                'user_id' => $group->owner_id,
                'reading_group_id' => $group->id,
                'role' => 'owner',
                'status' => 'approved',
                'joined_at' => $group->created_at,
            ]);

            // Add random members (2-8 members per group)
            $memberCount = rand(2, 8);
            $randomUsers = $users->where('id', '!=', $group->owner_id)
                                 ->random(min($memberCount, $users->count() - 1));

            foreach ($randomUsers as $user) {
                // 80% approved, 20% pending for private groups
                $status = $group->is_private && rand(1, 100) <= 20 ? 'pending' : 'approved';
                
                GroupMembership::create([
                    'user_id' => $user->id,
                    'reading_group_id' => $group->id,
                    'role' => 'member',
                    'status' => $status,
                    'joined_at' => $status === 'approved' ? now()->subDays(rand(1, 180)) : null,
                ]);
            }

            // Create 1-4 events for each group (70% of groups)
            if (rand(1, 100) <= 70) {
                $eventCount = rand(1, 4);
                
                for ($i = 0; $i < $eventCount; $i++) {
                    $isPast = $i < ($eventCount / 2); // Half past, half upcoming
                    
                    $event = GroupEvent::create([
                        'reading_group_id' => $group->id,
                        'title' => $this->getRandomEventTitle(),
                        'description' => $this->getRandomEventDescription(),
                        'event_date' => $isPast 
                            ? now()->subDays(rand(1, 90))->format('Y-m-d')
                            : now()->addDays(rand(1, 60))->format('Y-m-d'),
                        'event_time' => rand(0, 1) ? sprintf('%02d:00', rand(14, 20)) : null,
                        'location' => $this->getRandomLocation(),
                        'max_attendees' => rand(0, 1) ? rand(5, 15) : null,
                        'created_by' => $group->owner_id,
                    ]);

                    // Add some attendees (1-5 random members)
                    $members = $group->members()
                        ->wherePivot('group_memberships.status', 'approved')
                        ->get();
                    if ($members->count() > 0) {
                        $attendeeCount = min(rand(1, 5), $members->count());
                        $attendees = $members->random($attendeeCount);

                        foreach ($attendees as $attendee) {
                            $event->attendees()->attach($attendee->id, [
                                'status' => 'confirmed',
                                'joined_at' => now()->subDays(rand(1, 30)),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        });

        $this->command->info('✓ Created ' . $groups->count() . ' reading groups with members and events!');
    }

    private function getRandomEventTitle(): string
    {
        $titles = [
            'Chapter 5 Discussion',
            'Book Club Meeting',
            'Author Q&A Session',
            'Monthly Meetup',
            'Reading Challenge Kickoff',
            'Genre Exploration Night',
            'Book Swap Event',
            'Literary Trivia Night',
            'Poetry Reading Evening',
            'Book Launch Party',
            'Reading Marathon',
            'Bookstore Visit',
            'Online Reading Session',
            'End of Year Celebration',
            'New Members Welcome',
        ];

        return $titles[array_rand($titles)];
    }

    private function getRandomEventDescription(): string
    {
        $descriptions = [
            'Join us for an engaging discussion about this month\'s book selection.',
            'Let\'s meet to share our thoughts and insights on our current read.',
            'A casual gathering to connect with fellow book lovers.',
            'Bring your favorite quotes and let\'s dive deep into the themes.',
            'Virtual meetup - grab your coffee and join us online!',
            'In-person gathering at our favorite local spot.',
            'Special guest speaker will join our discussion.',
            'Potluck style - bring snacks and your reading list!',
            'Relaxed atmosphere, all reading levels welcome.',
            'Deep dive into character development and plot twists.',
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function getRandomLocation(): string
    {
        $locations = [
            'Central Library - Room 202',
            'Starbucks Downtown',
            'The Book Nook Cafe',
            'Online via Zoom',
            'City Park Pavilion',
            'Community Center',
            'Barnes & Noble Reading Room',
            'Member\'s Home',
            'Local Coffee Shop',
            'Virtual Meeting',
            'Public Library',
            'University Campus',
        ];

        return $locations[array_rand($locations)];
    }
}
