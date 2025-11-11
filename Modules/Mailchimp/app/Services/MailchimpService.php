<?php

namespace Modules\Mailchimp\app\Services;

use App\Models\User;
use App\Models\Integration;
use Illuminate\Support\Facades\Log;
use MailchimpMarketing\ApiClient;

class MailchimpService
{
    protected ?ApiClient $client = null;
    protected ?string $listId = null;
    protected ?array $config = null;

    /**
     * Initialize Mailchimp client
     */
    public function __construct()
    {
        $this->initializeClient();
    }

    /**
     * Initialize the Mailchimp API client
     */
    private function initializeClient(): void
    {
        try {
            $integration = Integration::where('module_name', 'Mailchimp')->first();

            if (!$integration || !$integration->is_enabled || !$integration->is_configured) {
                Log::warning('Mailchimp integration not enabled or configured');
                return;
            }

            $this->config = $integration->config ?? [];
            $apiKey = $this->config['api_key'] ?? null;
            $this->listId = $this->config['list_id'] ?? null;

            if (!$apiKey || !$this->listId) {
                Log::error('Mailchimp API key or List ID missing');
                return;
            }

            // Extract server prefix from API key (e.g., us1, us2, etc.)
            $server = explode('-', $apiKey)[1] ?? 'us1';

            $this->client = new ApiClient();
            $this->client->setConfig([
                'apiKey' => $apiKey,
                'server' => $server,
            ]);

            Log::info('Mailchimp client initialized', ['server' => $server]);

        } catch (\Exception $e) {
            Log::error('Error initializing Mailchimp client', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Check if Mailchimp is configured and ready
     */
    public function isConfigured(): bool
    {
        return $this->client !== null && $this->listId !== null;
    }

    /**
     * Subscribe a user to Mailchimp
     */
    public function subscribeMember(User $user, array $mergeFields = [], array $tags = []): bool
    {
        if (!$this->isConfigured()) {
            Log::warning('Mailchimp not configured, skipping subscribe');
            return false;
        }

        try {
            $subscriberHash = md5(strtolower($user->email));

            // Prepare member data
            $memberData = [
                'email_address' => $user->email,
                'status' => $this->shouldDoubleOptIn() ? 'pending' : 'subscribed',
                'merge_fields' => array_merge([
                    'FNAME' => $user->name ?? '',
                    'LNAME' => $user->surname ?? '',
                ], $mergeFields),
            ];

            // Add or update member
            $response = $this->client->lists->setListMember(
                $this->listId,
                $subscriberHash,
                $memberData
            );

            // Add tags if provided
            if (!empty($tags)) {
                $this->addTagsToMember($user->email, $tags);
            }

            // Update user record
            $user->update([
                'mailchimp_subscriber_id' => $response->id ?? $subscriberHash,
                'mailchimp_status' => $response->status ?? 'subscribed',
                'mailchimp_synced_at' => now(),
            ]);

            Log::info('User subscribed to Mailchimp', [
                'user_id' => $user->id,
                'email' => $user->email,
                'status' => $response->status,
            ]);

            return true;

        } catch (\MailchimpMarketing\ApiException $e) {
            Log::error('Mailchimp API error during subscribe', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error subscribing user to Mailchimp', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
            return false;
        }
    }

    /**
     * Unsubscribe a user from Mailchimp
     */
    public function unsubscribeMember(User $user): bool
    {
        if (!$this->isConfigured()) {
            Log::warning('Mailchimp not configured, skipping unsubscribe');
            return false;
        }

        try {
            $subscriberHash = md5(strtolower($user->email));

            $this->client->lists->updateListMember(
                $this->listId,
                $subscriberHash,
                ['status' => 'unsubscribed']
            );

            // Update user record
            $user->update([
                'mailchimp_status' => 'unsubscribed',
                'mailchimp_synced_at' => now(),
            ]);

            Log::info('User unsubscribed from Mailchimp', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return true;

        } catch (\MailchimpMarketing\ApiException $e) {
            Log::error('Mailchimp API error during unsubscribe', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error unsubscribing user from Mailchimp', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
            return false;
        }
    }

    /**
     * Update member information in Mailchimp
     */
    public function updateMember(User $user, array $mergeFields = []): bool
    {
        if (!$this->isConfigured()) {
            Log::warning('Mailchimp not configured, skipping update');
            return false;
        }

        try {
            $subscriberHash = md5(strtolower($user->email));

            $updateData = [
                'merge_fields' => array_merge([
                    'FNAME' => $user->name ?? '',
                    'LNAME' => $user->surname ?? '',
                ], $mergeFields),
            ];

            $this->client->lists->updateListMember(
                $this->listId,
                $subscriberHash,
                $updateData
            );

            // Update sync timestamp
            $user->update([
                'mailchimp_synced_at' => now(),
            ]);

            Log::info('User updated in Mailchimp', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return true;

        } catch (\MailchimpMarketing\ApiException $e) {
            Log::error('Mailchimp API error during update', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error updating user in Mailchimp', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
            return false;
        }
    }

    /**
     * Get member information from Mailchimp
     */
    public function getMemberInfo(string $email): ?object
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $subscriberHash = md5(strtolower($email));

            $response = $this->client->lists->getListMember(
                $this->listId,
                $subscriberHash
            );

            return $response;

        } catch (\MailchimpMarketing\ApiException $e) {
            Log::warning('Member not found in Mailchimp', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Error getting member info from Mailchimp', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Add tags to a member
     */
    public function addTagsToMember(string $email, array $tags): bool
    {
        if (!$this->isConfigured() || empty($tags)) {
            return false;
        }

        try {
            $subscriberHash = md5(strtolower($email));

            $tagData = [
                'tags' => array_map(function ($tag) {
                    return [
                        'name' => $tag,
                        'status' => 'active',
                    ];
                }, $tags),
            ];

            $this->client->lists->updateListMemberTags(
                $this->listId,
                $subscriberHash,
                $tagData
            );

            Log::info('Tags added to member in Mailchimp', [
                'email' => $email,
                'tags' => $tags,
            ]);

            return true;

        } catch (\MailchimpMarketing\ApiException $e) {
            Log::error('Mailchimp API error adding tags', [
                'error' => $e->getMessage(),
                'email' => $email,
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error adding tags to member', [
                'error' => $e->getMessage(),
                'email' => $email,
            ]);
            return false;
        }
    }

    /**
     * Remove tags from a member
     */
    public function removeTagsFromMember(string $email, array $tags): bool
    {
        if (!$this->isConfigured() || empty($tags)) {
            return false;
        }

        try {
            $subscriberHash = md5(strtolower($email));

            $tagData = [
                'tags' => array_map(function ($tag) {
                    return [
                        'name' => $tag,
                        'status' => 'inactive',
                    ];
                }, $tags),
            ];

            $this->client->lists->updateListMemberTags(
                $this->listId,
                $subscriberHash,
                $tagData
            );

            Log::info('Tags removed from member in Mailchimp', [
                'email' => $email,
                'tags' => $tags,
            ]);

            return true;

        } catch (\MailchimpMarketing\ApiException $e) {
            Log::error('Mailchimp API error removing tags', [
                'error' => $e->getMessage(),
                'email' => $email,
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error removing tags from member', [
                'error' => $e->getMessage(),
                'email' => $email,
            ]);
            return false;
        }
    }

    /**
     * Get all lists from Mailchimp
     */
    public function getLists(): ?array
    {
        if (!$this->client) {
            return null;
        }

        try {
            $response = $this->client->lists->getAllLists();
            return $response->lists ?? [];

        } catch (\MailchimpMarketing\ApiException $e) {
            Log::error('Mailchimp API error getting lists', [
                'error' => $e->getMessage(),
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Error getting lists from Mailchimp', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Test the Mailchimp connection
     */
    public function testConnection(): array
    {
        if (!$this->client) {
            return [
                'success' => false,
                'message' => 'Mailchimp client not initialized',
            ];
        }

        try {
            $response = $this->client->ping->get();

            return [
                'success' => true,
                'message' => 'Connection successful',
                'data' => $response,
            ];

        } catch (\MailchimpMarketing\ApiException $e) {
            return [
                'success' => false,
                'message' => 'API Error: ' . $e->getMessage(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Sync user to Mailchimp (add or update)
     */
    public function syncUser(User $user): bool
    {
        if (!$this->shouldAutoAddMembers()) {
            return false;
        }

        // Check if user already exists in Mailchimp
        $memberInfo = $this->getMemberInfo($user->email);

        if ($memberInfo) {
            // Update existing member
            return $this->updateMember($user);
        } else {
            // Subscribe new member
            return $this->subscribeMember($user);
        }
    }

    /**
     * Check if double opt-in is enabled
     */
    private function shouldDoubleOptIn(): bool
    {
        return $this->config['double_optin'] ?? false;
    }

    /**
     * Check if auto-add members is enabled
     */
    private function shouldAutoAddMembers(): bool
    {
        return $this->config['add_members_auto'] ?? false;
    }
}

