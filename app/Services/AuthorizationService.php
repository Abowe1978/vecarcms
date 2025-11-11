<?php

namespace App\Services;

use App\Services\Interfaces\AuthorizationServiceInterface;
use Illuminate\Support\Facades\Gate;

class AuthorizationService implements AuthorizationServiceInterface
{
    /**
     * Authorize viewing any resource of the given type.
     *
     * @param string $resourceType
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeViewAny(string $resourceType): void
    {
        Gate::authorize('viewAny', $resourceType);
    }

    /**
     * Authorize creating a resource of the given type.
     *
     * @param string $resourceType
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeCreate(string $resourceType): void
    {
        Gate::authorize('create', $resourceType);
    }

    /**
     * Authorize updating the given resource.
     *
     * @param mixed $resource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeUpdate(mixed $resource): void
    {
        Gate::authorize('update', $resource);
    }

    /**
     * Authorize deleting the given resource.
     *
     * @param mixed $resource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeDelete(mixed $resource): void
    {
        Gate::authorize('delete', $resource);
    }
} 