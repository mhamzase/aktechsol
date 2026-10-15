<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Newsletter subscribers + AK Tech Sol platform tables
|--------------------------------------------------------------------------
| NOTE (dev phase): this project is pre-launch, so ALL new schema for the
| agency automation flow lives in this existing migration file instead of
| separate migration files. The owner runs `migrate:fresh --seed` locally.
|
| Tables in this file:
|   1. newsletter_subscribers      (existing)
|   2. quote_requests              visitor quote enquiries
|   3. proposals                   formal proposals sent against a quote
|   4. client_projects             projects created from accepted proposals
|   5. project_updates             timeline entries shown on the tracking link
|   6. invoices                    milestone / project invoices
|   7. invoice_payments            client payment proofs (screenshot upload)
|   8. reviews                     client + team reviews after delivery
|   9. chatbot_queries             stored Q&A for the keyword chatbot
|  10. service_packages            pricing packages (Starter / Growth / Pro)
|  11. jobs                        job / internship listings
|  12. job_applications            applications with portfolio links
|  13. demo_bookings               "Book a Demo" requests
|  14. activity_logs               admin activity / audit trail
*/

return new class extends Migration {
    public function up(): void
    {
        /* ------------------------------------------------------------------
         | 1. Newsletter subscribers (existing table, untouched)
         | ------------------------------------------------------------------ */
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->boolean('is_subscribed')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        /* ------------------------------------------------------------------
         | 2. Quote requests — submitted from the public "Get a Quote" form.
         | ------------------------------------------------------------------ */
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('subject')->nullable();
            $table->text('requirements');
            $table->string('budget_range')->nullable();
            // new | reviewed | quoted | rejected
            $table->string('status')->default('new')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        /* ------------------------------------------------------------------
         | 3. Proposals — built by admin from a quote request. The client
         |    opens it through a secure token link, no login required.
         | ------------------------------------------------------------------ */
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_request_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('client_name');
            $table->string('client_email');
            $table->text('scope');
            $table->string('timeline')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->json('milestones')->nullable();
            $table->date('valid_until')->nullable()->comment('Proposal expiry date');
            // draft | sent | accepted | rejected | changes_requested | expired
            $table->string('status')->default('draft')->index();
            $table->string('token', 64)->unique()->comment('Secure public link token');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable()->comment('First time client opened the link');
            $table->timestamp('responded_at')->nullable();
            $table->text('client_note')->nullable()->comment('Note left by client on respond');
            $table->timestamps();
            $table->softDeletes();
        });

        /* ------------------------------------------------------------------
         | 4. Client projects — created when a proposal is accepted.
         |    Tracked publicly via the secure token link.
         | ------------------------------------------------------------------ */
        Schema::create('client_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('client_name');
            $table->string('client_email');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete()
                ->comment('Team member responsible (AK Tech Sol side)');
            // received | in_progress | review | revision | delivered
            $table->string('status')->default('received')->index();
            $table->unsignedTinyInteger('progress')->default(0)->comment('0-100 percent');
            $table->string('token', 64)->unique()->comment('Secure tracking link token');
            $table->date('started_at')->nullable();
            $table->date('deadline')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        /* ------------------------------------------------------------------
         | 5. Project updates — timeline entries for the tracking link.
         | ------------------------------------------------------------------ */
        Schema::create('project_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_client_visible')->default(true);
            $table->timestamps();
        });

        /* ------------------------------------------------------------------
         | 6. Invoices — milestone / project invoices (manual payments).
         | ------------------------------------------------------------------ */
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('invoice_no')->unique();
            $table->string('title');
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->date('due_date')->nullable();
            // unpaid | partially_paid | paid | overdue | cancelled
            $table->string('status')->default('unpaid')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        /* ------------------------------------------------------------------
         | 7. Invoice payments — client uploads a payment screenshot as
         |    proof; admin verifies it manually.
         | ------------------------------------------------------------------ */
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('method')->nullable()->comment('JazzCash / Easypaisa / Bank ...');
            $table->string('reference')->nullable()->comment('Transaction ID / ref given by client');
            // pending | verified | rejected
            $table->string('status')->default('pending')->index();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        /* ------------------------------------------------------------------
         | 8. Reviews — client + team reviews collected after delivery.
         | ------------------------------------------------------------------ */
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_project_id')->constrained()->cascadeOnDelete();
            // client | team
            $table->string('reviewer_type')->default('client')->index();
            $table->string('reviewer_name');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('review')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        /* ------------------------------------------------------------------
         | 9. Chatbot queries — stored Q&A for the keyword chatbot.
         |    Unanswered visitor questions are stored with a null answer
         |    for the team to answer manually from the admin panel.
         | ------------------------------------------------------------------ */
        Schema::create('chatbot_queries', function (Blueprint $table) {
            $table->id();
            $table->text('query');
            $table->text('answer')->nullable();
            $table->boolean('is_answered')->default(false)->index();
            $table->string('session_id')->nullable()->index();
            $table->timestamps();
        });

        /* ------------------------------------------------------------------
         | 10. Service packages — pricing tiers (Starter / Growth / Pro).
         | ------------------------------------------------------------------ */
        Schema::create('service_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->string('price_label')->nullable()->comment('e.g. "Starting from"');
            $table->string('turnaround')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        /* ------------------------------------------------------------------
         | 11. Job listings — job / internship listings.
         |     (Named `job_listings` because Laravel's queue already
         |      owns the `jobs` table name.)
         | ------------------------------------------------------------------ */
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            // job | internship
            $table->string('type')->default('job')->index();
            // remote | onsite | hybrid
            $table->string('location_type')->default('remote');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->boolean('is_open')->default(true)->index();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        /* ------------------------------------------------------------------
         | 12. Job applications — portfolio link is required.
         | ------------------------------------------------------------------ */
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_listings')->cascadeOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('portfolio_url')->comment('Required — applications without work samples are not reviewed');
            $table->text('cover_note')->nullable();
            // new | reviewed | shortlisted | rejected
            $table->string('status')->default('new')->index();
            $table->timestamps();
        });

        /* ------------------------------------------------------------------
         | 13. Demo bookings — "Book a Demo" requests.
         | ------------------------------------------------------------------ */
        Schema::create('demo_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('service')->nullable();
            $table->date('preferred_date')->nullable();
            $table->string('preferred_time')->nullable();
            $table->text('message')->nullable();
            // new | contacted | scheduled | done | cancelled
            $table->string('status')->default('new')->index();
            $table->timestamps();
        });

        /* ------------------------------------------------------------------
         | 14. Activity logs — simple audit trail for admin actions.
         | ------------------------------------------------------------------ */
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action')->index()->comment('e.g. quote.status_changed');
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        /* ------------------------------------------------------------------
         | 15. Notifications — Laravel database notifications for admins
         |     (new quote, unanswered chat, payment proof, ...).
         | ------------------------------------------------------------------ */
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('demo_bookings');
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_listings');
        Schema::dropIfExists('service_packages');
        Schema::dropIfExists('chatbot_queries');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('invoice_payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('project_updates');
        Schema::dropIfExists('client_projects');
        Schema::dropIfExists('proposals');
        Schema::dropIfExists('quote_requests');
        Schema::dropIfExists('newsletter_subscribers');
    }
};
