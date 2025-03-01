<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Accident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AccidentController
 */
final class AccidentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $accidents = Accident::factory()->count(3)->create();

        $response = $this->get(route('accidents.index'));

        $response->assertOk();
        $response->assertViewIs('accident.index');
        $response->assertViewHas('accidents');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('accidents.create'));

        $response->assertOk();
        $response->assertViewIs('accident.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AccidentController::class,
            'store',
            \App\Http\Requests\AccidentStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $user = User::factory()->create();
        $location = fake()->word();
        $date = Carbon::parse(fake()->dateTime());
        $description = fake()->text();
        $damage_estimate = fake()->randomFloat(/** float_attributes **/);
        $longitude = fake()->longitude();
        $latitude = fake()->latitude();

        $response = $this->post(route('accidents.store'), [
            'user_id' => $user->id,
            'location' => $location,
            'date' => $date->toDateTimeString(),
            'description' => $description,
            'damage_estimate' => $damage_estimate,
            'longitude' => $longitude,
            'latitude' => $latitude,
        ]);

        $accidents = Accident::query()
            ->where('user_id', $user->id)
            ->where('location', $location)
            ->where('date', $date)
            ->where('description', $description)
            ->where('damage_estimate', $damage_estimate)
            ->where('longitude', $longitude)
            ->where('latitude', $latitude)
            ->get();
        $this->assertCount(1, $accidents);
        $accident = $accidents->first();

        $response->assertRedirect(route('accidents.index'));
        $response->assertSessionHas('accident.id', $accident->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $accident = Accident::factory()->create();

        $response = $this->get(route('accidents.show', $accident));

        $response->assertOk();
        $response->assertViewIs('accident.show');
        $response->assertViewHas('accident');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $accident = Accident::factory()->create();

        $response = $this->get(route('accidents.edit', $accident));

        $response->assertOk();
        $response->assertViewIs('accident.edit');
        $response->assertViewHas('accident');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AccidentController::class,
            'update',
            \App\Http\Requests\AccidentUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $accident = Accident::factory()->create();
        $user = User::factory()->create();
        $location = fake()->word();
        $date = Carbon::parse(fake()->dateTime());
        $description = fake()->text();
        $damage_estimate = fake()->randomFloat(/** float_attributes **/);
        $longitude = fake()->longitude();
        $latitude = fake()->latitude();

        $response = $this->put(route('accidents.update', $accident), [
            'user_id' => $user->id,
            'location' => $location,
            'date' => $date->toDateTimeString(),
            'description' => $description,
            'damage_estimate' => $damage_estimate,
            'longitude' => $longitude,
            'latitude' => $latitude,
        ]);

        $accident->refresh();

        $response->assertRedirect(route('accidents.index'));
        $response->assertSessionHas('accident.id', $accident->id);

        $this->assertEquals($user->id, $accident->user_id);
        $this->assertEquals($location, $accident->location);
        $this->assertEquals($date, $accident->date);
        $this->assertEquals($description, $accident->description);
        $this->assertEquals($damage_estimate, $accident->damage_estimate);
        $this->assertEquals($longitude, $accident->longitude);
        $this->assertEquals($latitude, $accident->latitude);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $accident = Accident::factory()->create();

        $response = $this->delete(route('accidents.destroy', $accident));

        $response->assertRedirect(route('accidents.index'));

        $this->assertModelMissing($accident);
    }
}
