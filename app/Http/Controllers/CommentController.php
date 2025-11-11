<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function __construct(
        protected CommentService $commentService
    ) {}

    /**
     * Store a new comment
     */
    public function store(Request $request): RedirectResponse
    {
        // Check if comments are enabled
        if (!settings('comments_enabled', true)) {
            return back()->with('error', 'I commenti sono attualmente disabilitati.');
        }

        // Validate based on whether user is authenticated
        $rules = [
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'content' => 'required|string|min:3|max:5000',
            'parent_id' => 'nullable|exists:comments,id',
        ];

        // If guest comments are allowed and user is not authenticated
        if (!auth()->check() && settings('comments_allow_guests', false)) {
            $rules['author_name'] = 'required|string|max:255';
            $rules['author_email'] = 'required|email|max:255';
            $rules['author_url'] = 'nullable|url|max:255';
            
            // reCAPTCHA for guests
            if (config('services.recaptcha.enabled')) {
                $rules['g-recaptcha-response'] = 'required|recaptcha';
            }
        }

        $validated = $request->validate($rules);

        // Spam detection
        $isSpam = $this->commentService->detectSpam(
            $validated['content'],
            $validated['author_email'] ?? null
        );

        if ($isSpam) {
            return back()->with('error', 'Il tuo commento è stato identificato come spam.');
        }

        // Create comment
        try {
            $comment = $this->commentService->createComment($validated);

            $message = $comment->isApproved()
                ? 'Commento pubblicato con successo!'
                : 'Commento inviato! Sarà visibile dopo la moderazione.';

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Errore durante la pubblicazione del commento.')
                ->withInput();
        }
    }

    /**
     * Update user's own comment
     */
    public function update(Request $request, Comment $comment): RedirectResponse
    {
        // Check authorization
        if (!$comment->canEdit()) {
            abort(403, 'Non puoi modificare questo commento.');
        }

        $validated = $request->validate([
            'content' => 'required|string|min:3|max:5000',
        ]);

        $this->commentService->updateComment($comment, $validated);

        return back()->with('success', 'Commento aggiornato con successo!');
    }

    /**
     * Delete user's own comment
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        // Check authorization
        if (!$comment->canDelete()) {
            abort(403, 'Non puoi eliminare questo commento.');
        }

        $this->commentService->deleteComment($comment);

        return back()->with('success', 'Commento eliminato!');
    }

    /**
     * Like a comment
     */
    public function like(Comment $comment): RedirectResponse
    {
        $comment->increment('likes');

        return back()->with('success', 'Grazie per il tuo feedback!');
    }
}

