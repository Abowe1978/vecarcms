<?php

namespace Tests\Unit\Repositories;

use App\Models\Plan;
use App\Repositories\MembershipRepository;
use Illuminate\Support\Facades\DB;
use Database\Factories\MembershipFactory;
use Faker\Factory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MembershipRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private MembershipRepository $repository;
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new MembershipRepository(new Plan());
        $this->faker = Factory::create();
    }

    /**
     * Test creating a membership.
     */
    public function testCreateMembership()
    {
        // Arrange
        $data = MembershipFactory::new()->definition();

        // Act
        $membership = $this->repository->createMembership($data);

        // Assert
        $this->assertInstanceOf(Plan::class, $membership);
        $this->assertEquals($data['name'], $membership->name);
        $this->assertEquals($data['description'], $membership->description);
        $this->assertEquals($data['price'], $membership->price);
        $this->assertEquals($data['trial_days'], $membership->trial_days);
        $this->assertEquals($data['stripe_plan_id'], $membership->stripe_plan_id);

        // Sort features (as before)
        $dataFeatures = $data['features'];
        $membershipFeatures = $membership->features;
        ksort($dataFeatures);
        ksort($membershipFeatures);

        $this->assertEquals($dataFeatures, $membershipFeatures);

        // Get the data from the database directly
        $databaseMembership = DB::table('memberships')->find($membership->id);

        // Decode the JSON strings and compare as arrays
        $dataFeaturesDecoded = json_decode(json_encode($data['features']), true); // Encode and immediately decode
        $databaseFeaturesDecoded = json_decode($databaseMembership->features, true);

        ksort($dataFeaturesDecoded); // Sort decoded arrays
        ksort($databaseFeaturesDecoded);

        $this->assertEquals($dataFeaturesDecoded, $databaseFeaturesDecoded); // Finally, compare decoded arrays

        $this->assertDatabaseHas('memberships', [
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => number_format($data['price'], 2, '.', ''),
            'trial_days' => $data['trial_days'],
            'stripe_plan_id' => $data['stripe_plan_id'],
            // We no longer check the 'features' field here, as we've already compared it
        ]);
    }

    /**
     * Test updating a membership.
     */
    public function testUpdateMembership()
    {
        // Arrange (set up the data)
        $membership = Plan::factory()->create(); // Create an existing membership record
        $updatedData = MembershipFactory::new()->definition(); // Generate the updated data

        // Act (perform the action)
        $this->repository->updateMembership($membership, $updatedData); // Update the membership

        // Assert (check the results)

        // 1. Verify the main data (excluding features)
        $this->assertDatabaseHas('memberships', [
            'id' => $membership->id,
            'name' => $updatedData['name'],
            'description' => $updatedData['description'],
            'price' => number_format($updatedData['price'], 2, '.', ''), // Format the price
            'trial_days' => $updatedData['trial_days'],
            'stripe_plan_id' => $updatedData['stripe_plan_id'],
        ]);

        // 2. Retrieve the features from the database and decode them
        $databaseMembership = DB::table('memberships')->find($membership->id);
        $databaseFeatures = json_decode($databaseMembership->features, true); // Decode JSON to array

        // 3. Prepare the original features for comparison (ensure it's an associative array)
        $updatedDataFeatures = is_array($updatedData['features']) ?
            $updatedData['features'] :
            json_decode(json_encode($updatedData['features']), true); // Decode if it's not an array

        // 4. Sort both arrays for correct comparison
        ksort($updatedDataFeatures);
        ksort($databaseFeatures);

        // 5. Compare the features as arrays
        $this->assertEquals($updatedDataFeatures, $databaseFeatures);

        // 6. Verify that the Plan object's data has been updated (optional, but good practice)
        $membershipReloaded = Plan::find($membership->id);
        $this->assertEquals($updatedData['name'], $membershipReloaded->name);
        $this->assertEquals($updatedData['description'], $membershipReloaded->description);
        $this->assertEquals(number_format($updatedData['price'], 2, '.', ''), $membershipReloaded->price);
        $this->assertEquals($updatedData['trial_days'], $membershipReloaded->trial_days);
        $this->assertEquals($updatedData['stripe_plan_id'], $membershipReloaded->stripe_plan_id);
        $this->assertEquals($updatedDataFeatures, $membershipReloaded->features); // Compare features in the object
    }

    /**
     * Test deleting a membership.
     */
    public function testDeleteMembership()
    {
        // Arrange
        $membership = Plan::factory()->create();

        // Act
        $this->repository->deleteMembership($membership);

        // Assert
        $this->assertDatabaseMissing('memberships', ['id' => $membership->id]);
    }

    /**
     * Test finding a membership by ID.
     */
    public function testFindMembershipById()
    {
        // Arrange
        $membership = Plan::factory()->create();

        // Act
        $foundMembership = $this->repository->findOrFail($membership->id);

        // Assert
        $this->assertInstanceOf(Plan::class, $foundMembership);
        $this->assertEquals($membership->id, $foundMembership->id);
    }

    /**
     * Test finding a membership by ID that does not exist.
     */
    public function testFindMembershipByIdNotFound()
    {
        // Arrange
        $nonExistentId = 999;

        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->repository->findOrFail($nonExistentId);
    }

    /**
     * Test creating a membership with missing required data.
     */
    public function testCreateMembershipMissingData()
    {
        // Arrange
        $data = MembershipFactory::new()->definition();
        unset($data['name']); // Remove a required field

        // Assert
        $this->expectException(\Throwable::class); // Expecting a generic exception due to database constraints

        // Act
        $this->repository->createMembership($data);
    }

    /**
     * Test updating a membership with invalid data (e.g., non-numeric price).
     */
    public function testUpdateMembershipInvalidData()
    {
        // Arrange
        $membership = Plan::factory()->create();
        $invalidData = MembershipFactory::new()->definition();
        $invalidData['price'] = 'abc'; // Non-numeric price

        // Assert
        $this->expectException(\Throwable::class); // Expecting a generic exception due to database constraints

        // Act
        $this->repository->updateMembership($membership, $invalidData);
    }
}
