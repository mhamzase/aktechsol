<?php

namespace Database\Seeders;

use App\Models\ChatbotQuery;
use App\Models\ClientProject;
use App\Models\DemoBooking;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\ProjectUpdate;
use App\Models\Proposal;
use App\Models\QuoteRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * PlatformSeeder — demo data for the agency automation flow.
 * Only runs on `migrate:fresh --seed` (dev phase).
 */
class PlatformSeeder extends Seeder
{
    public function run(): void
    {
        // Jobs
        $job = Job::firstOrCreate(['slug' => 'video-editor-remote'], [
            'title' => 'Video Editor (Remote)', 'type' => 'job', 'location_type' => 'remote',
            'description' => 'Edit short-form content for our clients.',
            'requirements' => 'Premiere Pro, 2+ years experience, portfolio required.',
            'is_open' => true, 'sort_order' => 1,
        ]);
        Job::firstOrCreate(['slug' => 'graphic-design-intern'], [
            'title' => 'Graphic Design Intern', 'type' => 'internship', 'location_type' => 'hybrid',
            'description' => 'Learn branding and social media design with our team.',
            'requirements' => 'Figma basics, portfolio required.',
            'is_open' => true, 'sort_order' => 2,
        ]);

        JobApplication::firstOrCreate(['email' => 'applicant@test.com'], [
            'job_id' => $job->id, 'name' => 'Demo Applicant', 'phone' => '03001234567',
            'portfolio_url' => 'https://behance.net/demo', 'status' => 'new',
        ]);

        // Chatbot Q&A
        foreach ([
            ['What services do you offer?', 'We offer web development, graphic design, video editing, UGC content and eCommerce listing services.'],
            ['What are your prices?', 'Every project is priced individually after we understand your requirements. Share the details through the Get a Quote form or book a free demo — our team will give you an exact price.'],
            ['How long does a project take?', 'Most projects take 7–30 days depending on scope. You will see live progress on your tracking link.'],
            ['Do you work with international clients?', 'Yes! We work with clients worldwide and communicate over WhatsApp, email and video calls.'],
            ['How do I pay?', 'We accept JazzCash, Easypaisa and bank transfer. You upload the payment screenshot and we verify it.'],
        ] as [$q, $a]) {
            ChatbotQuery::firstOrCreate(['query' => $q], ['answer' => $a, 'is_answered' => true]);
        }
        ChatbotQuery::firstOrCreate(
            ['query' => 'Do you offer logo design?'],
            ['answer' => null, 'is_answered' => false]
        );

        // Quotes
        $quote = QuoteRequest::firstOrCreate(['email' => 'client@test.com'], [
            'name' => 'Demo Client', 'phone' => '03009876543', 'company' => 'Demo Co',
            'subject' => 'Ecommerce website', 'requirements' => 'Need a 20-product Shopify-style store with JazzCash integration.',
            'budget_range' => 'PKR 30k – 50k', 'status' => 'new',
        ]);

        // Proposal from the quote.
        // NOTE: tokens are set explicitly because DatabaseSeeder uses
        // WithoutModelEvents (model `creating` hooks don't fire while seeding).
        $proposal = Proposal::firstOrCreate(['client_email' => 'client@test.com'], [
            'quote_request_id' => $quote->id, 'title' => 'Ecommerce Website — Demo Co',
            'client_name' => 'Demo Client', 'scope' => '20-product store, responsive design, payment integration, basic SEO.',
            'timeline' => '21 days', 'amount' => 45000,
            'milestones' => [['title' => 'Design', 'amount' => 15000], ['title' => 'Development', 'amount' => 30000]],
            'valid_until' => now()->addDays(14), 'status' => 'accepted', 'sent_at' => now()->subDays(6),
            'responded_at' => now()->subDays(5), 'token' => Str::random(48),
        ]);

        // Client project from the accepted proposal, with a demo team
        // (the admin + the first team member) assigned to it.
        $admin = User::first();
        $project = ClientProject::firstOrCreate(['proposal_id' => $proposal->id], [
            'title' => 'Ecommerce Website — Demo Co', 'client_name' => 'Demo Client',
            'client_email' => 'client@test.com',
            'status' => 'in_progress', 'progress' => 40,
            'started_at' => now()->subDays(4), 'deadline' => now()->addDays(17),
            'token' => Str::random(48),
        ]);
        $project->team()->sync(User::orderBy('id')->limit(2)->pluck('id'));

        ProjectUpdate::firstOrCreate(
            ['client_project_id' => $project->id, 'title' => 'Design approved'],
            ['note' => 'Homepage and product page designs approved by client.', 'created_by' => $admin?->id, 'is_client_visible' => true]
        );

        // Invoice + payment proof
        $invoice = Invoice::firstOrCreate(['invoice_no' => 'INV-' . now()->format('Ym') . '-0001'], [
            'client_project_id' => $project->id, 'title' => 'Milestone 1: Design',
            'amount' => 15000, 'paid_amount' => 0, 'due_date' => now()->addDays(7), 'status' => 'unpaid',
            'token' => Str::random(48),
        ]);
        InvoicePayment::firstOrCreate(
            ['invoice_id' => $invoice->id, 'reference' => 'TRX123456'],
            ['amount' => 15000, 'method' => 'JazzCash', 'status' => 'pending']
        );

        // Demo bookings
        DemoBooking::firstOrCreate(['email' => 'demo@test.com'], [
            'name' => 'Demo Booker', 'phone' => '03001112233', 'service' => 'Web Development',
            'preferred_date' => now()->addDays(2)->toDateString(), 'preferred_time' => '3:00 PM',
            'message' => 'Want a walkthrough of your process.', 'status' => 'new',
        ]);
    }
}
