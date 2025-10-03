<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Contact Feature Test Suite
 *
 * This test class contains comprehensive integration tests for the Contact API endpoints.
 * It verifies the complete CRUD (Create, Read, Update, Delete) functionality of the
 * contact management system.
 *
 * @package Tests\Feature
 * @covers \App\Http\Controllers\ContactController
 */
class ContactFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: List all contacts
     *
     * This test verifies that the API can retrieve a paginated list of all contacts.
     * It creates sample data, makes a GET request, and validates both the response
     * structure and the data count.
     *
     * @test
     * @group contacts
     * @group api
     *
     * @return void
     */
    public function it_can_list_contacts()
    {
        // Arrange: Create test data - 3 contacts using factory
        Contact::factory()->count(3)->create();

        // Act: Make GET request to contacts endpoint
        $response = $this->getJson('/api/contacts');

        // Assert: Validate response structure and content
        $response->assertStatus(200) // HTTP OK status
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'email', 'cpf', 'created_at', 'updated_at']
            ]
        ]) // Ensure proper JSON structure with required fields
        ->assertJsonCount(3, 'data'); // Verify exactly 3 contacts returned
    }

    /**
     * Test: Create a new contact
     *
     * This test validates the contact creation functionality. It sends a POST request
     * with valid contact data and verifies both the API response and database persistence.
     * Also tests CPF formatting (removes dots and hyphens).
     *
     * @test
     * @group contacts
     * @group api
     * @group creation
     *
     * @return void
     */
    public function it_can_create_a_contact()
    {
        // Arrange: Prepare test payload with formatted CPF
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'cpf' => '123.456.789-00' // CPF with formatting
        ];

        // Act: Send POST request to create contact
        $response = $this->postJson('/api/contacts', $payload);

        // Assert: Validate response and database state
        $response->assertStatus(201) // HTTP Created status
        ->assertJsonFragment([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'cpf' => '12345678900', // CPF stored without formatting
        ]);

        // Verify the contact was actually saved to database
        $this->assertDatabaseHas('contacts', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'cpf' => '12345678900', // Database stores normalized CPF
        ]);
    }

    /**
     * Test: Validation of required fields
     *
     * This test ensures that the API properly validates required fields during
     * contact creation. It sends an empty payload and expects validation errors
     * for all required fields.
     *
     * @test
     * @group contacts
     * @group api
     * @group validation
     *
     * @return void
     */
    public function it_validates_required_fields_on_creation()
    {
        // Act: Send POST request with empty payload (missing required fields)
        $response = $this->postJson('/api/contacts', []);

        // Assert: Expect validation errors for required fields
        $response->assertStatus(422) // HTTP Unprocessable Entity status
        ->assertJsonValidationErrors(['name', 'email', 'cpf']); // Required fields
    }

    /**
     * Test: Show a single contact
     *
     * This test verifies the functionality to retrieve a specific contact by its ID.
     * It creates a contact, makes a GET request with the ID, and validates the
     * returned data matches the created contact.
     *
     * @test
     * @group contacts
     * @group api
     * @group retrieval
     *
     * @return void
     */
    public function it_can_show_a_single_contact()
    {
        // Arrange: Create a contact using factory
        $contact = Contact::factory()->create();

        // Act: Request specific contact by ID
        $response = $this->getJson("/api/contacts/{$contact->id}");

        // Assert: Validate returned contact data
        $response->assertStatus(200) // HTTP OK status
        ->assertJson([
            'id' => $contact->id,
            'name' => $contact->name,
            'email' => $contact->email,
            'cpf' => $contact->cpf,
        ]); // Exact match of contact data
    }

    /**
     * Test: Update an existing contact
     *
     * This test validates the contact update functionality. It creates a contact,
     * sends a PUT request with updated data, and verifies both the response and
     * database changes. Also tests CPF normalization during updates.
     *
     * @test
     * @group contacts
     * @group api
     * @group update
     *
     * @return void
     */
    public function it_can_update_a_contact()
    {
        // Arrange: Create existing contact and prepare update data
        $contact = Contact::factory()->create();

        $payload = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'cpf' => '987.654.321-00' // Formatted CPF for testing normalization
        ];

        // Act: Send PUT request to update the contact
        $response = $this->putJson("/api/contacts/{$contact->id}", $payload);

        // Assert: Validate response contains updated data
        $response->assertStatus(200) // HTTP OK status
        ->assertJsonFragment([
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'cpf' => '98765432100', // Normalized CPF in response
        ]);

        // Verify database was updated with new values
        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id, // Same ID
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'cpf' => '98765432100', // Normalized CPF in database
        ]);
    }

    /**
     * Test: Delete a contact
     *
     * This test verifies the contact deletion functionality. It creates a contact,
     * sends a DELETE request, and confirms both the HTTP response and that the
     * record was removed from the database.
     *
     * @test
     * @group contacts
     * @group api
     * @group deletion
     *
     * @return void
     */
    public function it_can_delete_a_contact()
    {
        // Arrange: Create a contact to be deleted
        $contact = Contact::factory()->create();

        // Act: Send DELETE request for the contact
        $response = $this->deleteJson("/api/contacts/{$contact->id}");

        // Assert: Validate successful deletion
        $response->assertStatus(204); // HTTP No Content status

        // Verify the contact was removed from database
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }
}
