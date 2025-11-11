<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Services\ContactService;
use App\Repositories\ContactRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ContactSubmissionController extends Controller
{
    public function __construct(
        protected ContactService $contactService,
        protected ContactRepository $contactRepository
    ) {
        $this->middleware('permission:view_submissions');
    }

    /**
     * Display contact submissions
     */
    public function index(Request $request): View
    {
        $status = $request->get('status', 'all');
        
        $submissions = match($status) {
            'new' => $this->contactRepository->getNew(),
            default => $this->contactRepository->paginate(),
        };

        $stats = $this->contactService->getStatistics();

        return view('admin.contact.index', compact('submissions', 'stats', 'status'));
    }

    /**
     * Show submission detail
     */
    public function show(ContactSubmission $contactSubmission): View
    {
        // Mark as read automatically
        if ($contactSubmission->isNew()) {
            $this->contactService->markAsRead($contactSubmission);
        }

        return view('admin.contact.show', compact('contactSubmission'));
    }

    /**
     * Update submission (add notes)
     */
    public function update(Request $request, ContactSubmission $contactSubmission): RedirectResponse
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string',
            'status' => 'in:new,read,replied,archived,spam',
        ]);

        $this->contactRepository->update($contactSubmission, $validated);

        return back()->with('success', 'Note aggiornate!');
    }

    /**
     * Delete submission
     */
    public function destroy(ContactSubmission $contactSubmission): RedirectResponse
    {
        $this->contactRepository->delete($contactSubmission);

        return redirect()
            ->route('admin.contact.index')
            ->with('success', 'Submission eliminata!');
    }
}

