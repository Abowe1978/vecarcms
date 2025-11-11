<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function __construct(
        protected CommentService $commentService
    ) {
        $this->middleware('permission:manage_comments');
    }

    /**
     * Display a listing of comments
     */
    public function index(Request $request): View
    {
        $status = $request->get('status', 'all');
        
        $comments = $this->commentService->getCommentsForAdmin($status);
        $stats = $this->commentService->getCommentCounts();

        return view('admin.comments.index', compact('comments', 'stats', 'status'));
    }

    /**
     * Show the form for editing the specified comment
     */
    public function edit(Comment $comment): View
    {
        return view('admin.comments.edit', compact('comment'));
    }

    /**
     * Update the specified comment
     */
    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $this->commentService->updateComment($comment, $validated);

        return redirect()
            ->route('admin.comments.index')
            ->with('success', 'Commento aggiornato con successo!');
    }

    /**
     * Approve a comment
     */
    public function approve(Comment $comment): RedirectResponse
    {
        $this->commentService->approveComment($comment);

        return back()->with('success', 'Commento approvato!');
    }

    /**
     * Mark comment as spam
     */
    public function spam(Comment $comment): RedirectResponse
    {
        $this->commentService->markAsSpam($comment);

        return back()->with('success', 'Commento segnato come spam!');
    }

    /**
     * Move comment to trash
     */
    public function trash(Comment $comment): RedirectResponse
    {
        $this->commentService->moveToTrash($comment);

        return back()->with('success', 'Commento spostato nel cestino!');
    }

    /**
     * Remove the specified comment from storage
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $this->commentService->deleteComment($comment);

        return back()->with('success', 'Commento eliminato definitivamente!');
    }

    /**
     * Bulk actions on comments
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,spam,trash,delete',
            'comments' => 'required|array',
            'comments.*' => 'exists:comments,id',
        ]);

        $count = match($validated['action']) {
            'approve' => $this->commentService->approveMultiple($validated['comments']),
            'delete' => $this->commentService->deleteMultiple($validated['comments']),
            default => 0,
        };

        $message = match($validated['action']) {
            'approve' => "{$count} commenti approvati!",
            'spam' => "{$count} commenti segnati come spam!",
            'trash' => "{$count} commenti spostati nel cestino!",
            'delete' => "{$count} commenti eliminati!",
        };

        return back()->with('success', $message);
    }
}

