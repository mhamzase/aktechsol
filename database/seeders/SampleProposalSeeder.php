<?php

namespace Database\Seeders;

use App\Models\Proposal;
use Illuminate\Database\Seeder;

class SampleProposalSeeder extends Seeder
{
    public function run(): void
    {
        $proposal = Proposal::create([
            'title' => 'E-commerce Website Development Proposal',
            'client_name' => 'Ahmed Khan',
            'client_email' => 'ahmed@example.com',
            'scope' => '<p>Complete e-commerce website with product catalog, shopping cart, secure checkout, and admin dashboard.</p><ul><li>Responsive design (mobile/tablet/desktop)</li><li>Payment gateway integration</li><li>Order management system</li><li>SEO optimization</li></ul>',
            'timeline' => '6-8 weeks',
            'amount' => 4500.00,
            'valid_until' => now()->addDays(30)->format('Y-m-d'),
            'milestones' => [
                ['title' => 'Design & Wireframes', 'amount' => 1350],
                ['title' => 'Development', 'amount' => 2250],
                ['title' => 'Testing & Launch', 'amount' => 900],
            ],
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $this->command->info('Sample proposal created!');
        $this->command->info('Client link: ' . $proposal->publicUrl());
    }
}
