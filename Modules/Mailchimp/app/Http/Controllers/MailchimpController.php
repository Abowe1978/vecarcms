<?php

namespace Modules\Mailchimp\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Mailchimp\app\Services\MailchimpService;

class MailchimpController extends Controller
{
    protected MailchimpService $mailchimpService;

    public function __construct(MailchimpService $mailchimpService)
    {
        $this->mailchimpService = $mailchimpService;
    }

    /**
     * Subscribe a user to Mailchimp
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tags' => 'nullable|array',
        ]);

        $user = User::findOrFail($validated['user_id']);

        $result = $this->mailchimpService->subscribeMember(
            $user,
            [],
            $validated['tags'] ?? []
        );

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'User subscribed to Mailchimp successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to subscribe user to Mailchimp',
        ], 500);
    }

    /**
     * Unsubscribe a user from Mailchimp
     */
    public function unsubscribe(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($validated['user_id']);

        $result = $this->mailchimpService->unsubscribeMember($user);

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'User unsubscribed from Mailchimp successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to unsubscribe user from Mailchimp',
        ], 500);
    }

    /**
     * Sync a user to Mailchimp
     */
    public function sync(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($validated['user_id']);

        $result = $this->mailchimpService->syncUser($user);

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'User synced to Mailchimp successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to sync user to Mailchimp',
        ], 500);
    }

    /**
     * Bulk sync all users to Mailchimp
     */
    public function bulkSync(Request $request)
    {
        $users = User::whereNull('mailchimp_subscriber_id')
            ->orWhere('mailchimp_synced_at', '<', now()->subDays(7))
            ->get();

        $synced = 0;
        $failed = 0;

        foreach ($users as $user) {
            if ($this->mailchimpService->syncUser($user)) {
                $synced++;
            } else {
                $failed++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Synced {$synced} users, {$failed} failed",
            'synced' => $synced,
            'failed' => $failed,
        ]);
    }

    /**
     * Get lists from Mailchimp
     */
    public function getLists()
    {
        $lists = $this->mailchimpService->getLists();

        if ($lists === null) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get lists from Mailchimp',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'lists' => $lists,
        ]);
    }

    /**
     * Test Mailchimp connection
     */
    public function testConnection()
    {
        $result = $this->mailchimpService->testConnection();

        return response()->json($result);
    }

    /**
     * Add tags to a user
     */
    public function addTags(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tags' => 'required|array|min:1',
        ]);

        $user = User::findOrFail($validated['user_id']);

        $result = $this->mailchimpService->addTagsToMember(
            $user->email,
            $validated['tags']
        );

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Tags added successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to add tags',
        ], 500);
    }

    /**
     * Remove tags from a user
     */
    public function removeTags(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tags' => 'required|array|min:1',
        ]);

        $user = User::findOrFail($validated['user_id']);

        $result = $this->mailchimpService->removeTagsFromMember(
            $user->email,
            $validated['tags']
        );

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Tags removed successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to remove tags',
        ], 500);
    }
}
