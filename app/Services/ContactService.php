<?php

namespace App\Services;

use App\Models\ContactSubmission;
use App\Repositories\ContactRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactService
{
    public function __construct(
        protected ContactRepository $contactRepository
    ) {}

    public function createSubmission(array $data): ContactSubmission
    {
        // Add metadata
        $data['ip_address'] = request()->ip();
        $data['user_agent'] = request()->userAgent();
        $data['referer'] = request()->header('referer');

        $submission = $this->contactRepository->create($data);

        // Send notification email to admin
        $this->sendNotificationToAdmin($submission);

        Log::info('Contact form submitted', [
            'submission_id' => $submission->id,
            'email' => $submission->email,
        ]);

        return $submission;
    }

    public function markAsRead(ContactSubmission $submission): void
    {
        $submission->markAsRead();
    }

    public function markAsSpam(ContactSubmission $submission): void
    {
        $submission->markAsSpam();
    }

    protected function sendNotificationToAdmin(ContactSubmission $submission): void
    {
        try {
            $adminEmail = settings('site_email');
            
            // TODO: Create email notification class
            Log::info('Contact form notification', [
                'to' => $adminEmail,
                'from' => $submission->email,
            ]);

        } catch (\Exception $e) {
            Log::error('Error sending contact notification', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function getStatistics(): array
    {
        return $this->contactRepository->getCountByStatus();
    }
}

