<?php

namespace Modules\Stripe\app\Services\Interfaces;

interface StripeServiceInterface
{
    /**
     * Get the Stripe configuration
     *
     * @return array
     */
    public function getConfig(): array;

    /**
     * Update the Stripe configuration
     *
     * @param array $config
     * @return bool
     */
    public function updateConfig(array $config): bool;

    /**
     * Check if Stripe is configured
     *
     * @return bool
     */
    public function isConfigured(): bool;

    /**
     * Get Stripe public key
     *
     * @return string
     */
    public function getPublicKey(): string;

    /**
     * Get Stripe secret key
     *
     * @return string
     */
    public function getSecretKey(): string;

    /**
     * Get webhook secret
     *
     * @return string
     */
    public function getWebhookSecret(): string;

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency(): string;

    /**
     * Check if sandbox mode is enabled
     *
     * @return bool
     */
    public function isSandboxMode(): bool;

    /**
     * Get supported payment methods
     *
     * @return array
     */
    public function getPaymentMethods(): array;

    /**
     * Get statement descriptor
     *
     * @return string
     */
    public function getStatementDescriptor(): string;

    /**
     * Check if automatic tax is enabled
     *
     * @return bool
     */
    public function isAutomaticTaxEnabled(): bool;
} 