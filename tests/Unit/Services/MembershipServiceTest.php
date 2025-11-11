<?php

namespace Tests\Unit\Services;

use App\Models\Plan;
use App\Repositories\MembershipRepository;
use App\Services\MembershipService;
use Database\Factories\MembershipFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Log;
use Mockery;
use Stripe\Exception\CardException;
use Stripe\Exception\RateLimitException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\ApiException;
use Stripe\Plan as StripePlan;
use Tests\TestCase;

class MembershipServiceTest extends TestCase
{
    use DatabaseTransactions;

    private MembershipService $service;
    private MembershipRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(MembershipRepository::class);
        $this->service = new MembershipService($this->repository);
    }

    /**
     * @test
     */
    public function it_should_create_a_membership_successfully()
    {
        // Arrange
        $data = MembershipFactory::new()->definition();
        $stripePlan = $this->mockStripePlanCreation($data);

        // Act
        $membership = $this->service->createMembershipWithStripe($data);

        // Assert
        $this->assertInstanceOf(Plan::class, $membership);
        $this->repository->shouldHaveReceived('createMembership')->once()->with(array_merge($data, ['stripe_plan_id' => $stripePlan->id]));
    }

    /**
     * @test
     */
    public function it_should_throw_exception_when_creating_membership_with_missing_required_data()
    {
        // Arrange
        $data = MembershipFactory::new()->definition();
        unset($data['name']);

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Validation errors: name: The name field is required.');

        // Act
        $this->service->createMembershipWithStripe($data);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_when_creating_membership_with_invalid_price()
    {
        // Arrange
        $data = MembershipFactory::new()->definition();
        $data['price'] = 'invalid_price';

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Validation errors: price: The price must be a number.');

        // Act
        $this->service->createMembershipWithStripe($data);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_when_creating_stripe_plan_fails_with_card_exception()
    {
        // Arrange
        $data = MembershipFactory::new()->definition();
        $this->mockStripePlanCreation(null, new CardException('Card declined'));

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error during plan creation: Card declined');

        // Act
        $this->service->createMembershipWithStripe($data);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_when_creating_stripe_plan_fails_with_rate_limit_exception()
    {
        // Arrange
        $data = MembershipFactory::new()->definition();
        $this->mockStripePlanCreation(null, new RateLimitException('Too many requests made to the API too quickly'));

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Stripe rate limit error: Too many requests made to the API too quickly');

        // Act
        $this->service->createMembershipWithStripe($data);
    }

    // ... altri test per createMembershipWithStripe (es. InvalidRequestException, AuthenticationException, ApiException, ecc.)

    /**
     * @test
     */
    public function it_should_update_membership_successfully()
    {
        // Arrange
        $membership = Plan::factory()->create();
        $updatedData = MembershipFactory::new()->definition();
        $this->mockStripePlanUpdate($membership->stripe_plan_id);

        // Act
        $this->service->updateMembershipWithStripe($membership, $updatedData);

        // Assert
        $this->repository->shouldHaveReceived('updateMembership')->once()->with($membership, $updatedData);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_when_updating_membership_with_missing_required_data()
    {
        // Arrange
        $membership = Plan::factory()->create();
        $updatedData = MembershipFactory::new()->definition();
        unset($updatedData['name']);

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Validation errors: name: The name field is required.');

        // Act
        $this->service->updateMembershipWithStripe($membership, $updatedData);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_when_updating_membership_not_found()
    {
        // Arrange
        $membership = Plan::factory()->make(); // Crea un'istanza di Plan senza salvarla
        $updatedData = MembershipFactory::new()->definition();

        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->service->updateMembershipWithStripe($membership, $updatedData);
    }

    // ... altri test per updateMembershipWithStripe (es. eccezioni Stripe, aggiornamento parziale, ecc.)

    /**
     * @test
     */
    public function it_should_delete_membership_successfully()
    {
        // Arrange
        $membership = Plan::factory()->create();
        $this->mockStripePlanDeletion($membership->stripe_plan_id);

        // Act
        $this->service->deleteMembershipWithStripe($membership);

        // Assert
        $this->repository->shouldHaveReceived('deleteMembership')->once()->with($membership);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_when_deleting_membership_not_found()
    {
        // Arrange
        $membership = Plan::factory()->make(); // Crea un'istanza di Plan senza salvarla

        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->service->deleteMembershipWithStripe($membership);
    }

    // ... altri test per deleteMembershipWithStripe (es. eccezioni Stripe, ecc.)

    // ... altri metodi di test per altri scenari (es. ricerca di abbonamenti, gestione di errori specifici, ecc.)

    private function mockStripePlanCreation($data, $exception = null)
    {
        $plan = $exception ? null : StripePlan::create($data);
        StripePlan::shouldReceive('create')->andReturn($plan)->once();
        return $plan;
    }

    private function mockStripePlanUpdate($planId)
    {
        $plan = StripePlan::retrieve($planId);
        $plan->shouldReceive('save')->andReturn($plan)->once();
    }

    private function mockStripePlanDeletion($planId)
    {
        $plan = StripePlan::retrieve($planId);
        $plan->shouldReceive('delete')->andReturn(null)->once();
    }
}
