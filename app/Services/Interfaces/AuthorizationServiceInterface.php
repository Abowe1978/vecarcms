<?php

namespace App\Services\Interfaces;

interface AuthorizationServiceInterface
{
    /**
     * Authorize viewing any resource of the given type.
     *
     * @param string $resourceType
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeViewAny(string $resourceType): void;

    /**
     * Authorize creating a resource of the given type.
     *
     * @param string $resourceType
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeCreate(string $resourceType): void;

    /**
     * Authorize updating the given resource.
     *
     * @param mixed $resource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeUpdate(mixed $resource): void;

    /**
     * Authorize deleting the given resource.
     *
     * @param mixed $resource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeDelete(mixed $resource): void;
} 