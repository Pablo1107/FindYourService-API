<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test if a guest user can get list of all services
     * Related to ServicesController@index
     *
     * @return void
     */
    public function test_a_guest_can_get_services()
    {
        // Given
        $services = factory('App\Service', 3)->create();
        // When guest try to hit the endpoint /services
        $this->get('/api/services')
            ->assertStatus(200)
            ->assertJson($services->toArray());
        // Then it should return collection of services
    }

    /**
     * Test if a auth user can create a service
     * Related to ServicesController@store
     *
     * @return void
     */
    public function test_a_user_can_create_a_service()
    {
        // Given a user who is logged in
        $this->actingAs(factory('App\User')->create());
        // When they hit the endpoint /services to create a new service
        $this->post('/api/services', [
            'title' => 'New Service',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'address' => 'Yapeyu 73',
            'city' => 'Buenos Aires',
            'state' => 'Buenos Aires',
            'zipcode' => 'C1202ACA',
            'longitude' => -58.42,
            'latitude' => -34.61
        ]);
        // Then there should be a new service in the database
        $this->assertDatabaseHas('services', ['title' => 'New Service']);
    }

    /**
     * Test if a guest user cannot create a service
     * Related to ServicesController@store
     *
     * @return void
     */
    public function test_a_guest_may_not_create_service()
    {
        // Given guest user
        // When guest try to hit the endpoint /services
        $this->json('post', '/api/services')->assertStatus(401);
        // Then server should respond with status 401 unauthorized
    }

    /**
     * Test if a guest user can get a service
     * Related to ServicesController@show
     *
     * @return void
     */
    public function test_a_guest_can_get_a_service()
    {
        // Given
        $service = factory('App\Service')->create();
        // When guest try to hit the endpoint /services/{service}
        $this->get('/api/services/' . $service->id)
            ->assertStatus(200)
            ->assertJson($service->toArray());
        // Then it should return the service
    }

    /**
     * Test if a auth user can update a service
     * Related to ServicesController@update
     *
     * @return void
     */
    public function test_a_user_can_update_a_service()
    {
        // Given a user who is logged in
        $this->actingAs(factory('App\User')->create());
        // and a service in the database
        $service = factory('App\Service')->create();
        // When user try to update the service
        $this->json('patch', '/api/services/' . $service->id,
            [ 'title' => 'New Service' ])
            ->assertStatus(200)
            ->assertJson(['message' => 'Service Updated']);
        // Then it should return message 'Service Updated'
    }

    /**
     * Test if a guest user cannot update a service
     * Related to ServicesController@update
     *
     * @return void
     */
    public function test_a_user_cannot_update_a_service()
    {
        // Given a guest user and a service in the database
        $service = factory('App\Service')->create();
        // When guest try to update the service
        $this->json('patch', '/api/services/' . $service->id,
            [ 'title' => 'New Service' ])
            ->assertStatus(401);
        // Then it should return status 401 unauthorized
    }

    /**
     * Test if a auth user can delete a service
     * Related to ServicesController@destroy
     *
     * @return void
     */
    public function test_a_user_can_delete_a_service()
    {
        // Given a user who is logged in
        $this->actingAs(factory('App\User')->create());
        // and a service in the database
        $service = factory('App\Service')->create();
        // When user try to update the service
        $this->json('delete', '/api/services/' . $service->id)
            ->assertStatus(200)
            ->assertJson(['message' => 'Service Deleted']);
        // Then it should return message 'Service Updated'
    }

    /**
     * Test if a guest user cannot delete a service
     * Related to ServicesController@destroy
     *
     * @return void
     */
    public function test_a_user_cannot_delete_a_service()
    {
        // Given a guest user and a service in the database
        $service = factory('App\Service')->create();
        // When guest try to update the service
        $this->json('delete', '/api/services/' . $service->id)
            ->assertStatus(401);
        // Then it should return status 401 unauthorized
    }

    /**
     * Test if endpoint /api/me returns accurate data
     *
     * @return void
     */
    public function test_login_status()
    {
        // Given guest
        // When view hit /me status
        $this->get('/me')
            ->assertStatus(404);
        // Then server returns status 404 unauthorized
        // Given an auth user
        $user = factory('App\User')->create();
        $this->actingAs($user);
        // When view hit /login status
        $this->get('/api/me')
            ->assertStatus(200)
            ->assertJsonFragment([
                'email' => $user->email,
            ])
            ->assertSee(json_encode($user->toArray()));
        // Then server returns user
    }
}
