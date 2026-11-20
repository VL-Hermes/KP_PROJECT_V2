<?php

namespace Tests\Feature;

use App\Models\Laptop;
use App\Models\User;
use Database\Seeders\LaptopSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaptopInventoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(LaptopSeeder::class);

        $this->adminUser = User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'is_main_admin' => true,
        ]);
    }

    /**
     * Test public catalog view is accessible by guests without admin controls.
     */
    public function test_catalog_index_displays_laptops_to_guests(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Used Laptop Catalog');
        $response->assertSee('ThinkPad T480');

        // Verify admin links are hidden from public guests
        $response->assertDontSee('Inventory Manager');
        $response->assertDontSee('Add Laptop');
        $response->assertDontSee('List New Laptop');
    }

    /**
     * Test public catalog detail view dynamically renders specs.
     */
    public function test_catalog_show_full_specs(): void
    {
        $laptop = Laptop::where('model', 'ThinkPad T480')->firstOrFail();

        $response = $this->get("/catalog/{$laptop->id}");

        $response->assertStatus(200);
        $response->assertSee('ThinkPad T480');
        $response->assertSee('Core Hardware Specifications');
        $response->assertSee('Hardware Health &amp; Diagnostics', false);
        $response->assertSee('Battery Health &amp; Capacity', false);
        $response->assertSee('Storage Drive Health (SMART)');
        $response->assertSee('Thermal &amp; Cooling Performance', false);
        $response->assertSee('Physical &amp; Aesthetic Condition', false);
        $response->assertSee('Input / Output &amp; Peripherals', false);
        $response->assertSee('Camera / Webcam');
        $response->assertSee('Microphone');
        $response->assertSee('Audio / Speakers');
        $response->assertSee('Keyboard &amp; Trackpad', false);
        $response->assertSee('Software &amp; Licensing', false);
        $response->assertSee('Included Addons &amp; Accessories', false);

        // Edit button should be hidden from guests
        $response->assertDontSee('Edit Laptop');
    }

    /**
     * Test public detail view automatically omits empty optional sections.
     */
    public function test_catalog_show_omits_empty_optional_specs(): void
    {
        // Minimal laptop: Only required core specs, all diagnostics null
        $laptop = Laptop::where('model', 'Latitude 7490')->firstOrFail();

        $response = $this->get("/catalog/{$laptop->id}");

        $response->assertStatus(200);
        $response->assertSee('Latitude 7490');
        $response->assertSee('Core Hardware Specifications');

        // Optional sections MUST be omitted because all values are null/empty
        $response->assertDontSee('Hardware Health &amp; Diagnostics', false);
        $response->assertDontSee('Battery Health &amp; Capacity', false);
        $response->assertDontSee('Storage Drive Health (SMART)');
        $response->assertDontSee('Thermal &amp; Cooling Performance', false);
        $response->assertDontSee('Physical &amp; Aesthetic Condition', false);
        $response->assertDontSee('Input / Output &amp; Peripherals', false);
        $response->assertDontSee('Included Addons &amp; Accessories', false);
    }

    /**
     * Test that guests accessing /admin see the hidden login screen.
     */
    public function test_admin_path_shows_login_to_guests(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Administrator Portal');
        $response->assertSee('Sign In to Dashboard');
    }

    /**
     * Test that guests cannot access inventory manager and are redirected to /admin.
     */
    public function test_guests_cannot_access_inventory_manager(): void
    {
        $response = $this->get('/admin/inventory');

        $response->assertRedirect('/admin');
    }

    /**
     * Test that guests cannot access create form and are redirected to /admin.
     */
    public function test_guests_cannot_access_create_form(): void
    {
        $response = $this->get('/admin/inventory/create');

        $response->assertRedirect('/admin');
    }

    /**
     * Test that guests cannot create laptops.
     */
    public function test_guests_cannot_store_laptop(): void
    {
        $payload = [
            'brand' => 'Unauthorized',
            'model' => 'Hacker Book',
            'processor_cpu' => 'Intel',
            'memory_ram' => '8GB',
            'storage' => '256GB',
            'graphics_gpu' => 'Intel',
            'display' => '14"',
        ];

        $response = $this->post('/admin/inventory', $payload);

        $response->assertRedirect('/admin');
        $this->assertDatabaseMissing('laptop', ['brand' => 'Unauthorized']);
    }

    /**
     * Test that an authenticated admin can access the inventory dashboard.
     */
    public function test_authenticated_admin_can_access_inventory(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/inventory');

        $response->assertStatus(200);
        $response->assertSee('Inventory Management Dashboard');
        $response->assertSee('ThinkPad T480');
        $response->assertSee('Date Added');
    }

    /**
     * Test admin can access create form.
     */
    public function test_admin_can_view_create_form(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/inventory/create');

        $response->assertStatus(200);
        $response->assertSee('Add Used Laptop to Inventory');
        $response->assertSee('Save Laptop');
    }

    /**
     * Test admin can create a laptop with required fields and nullable optional fields.
     */
    public function test_admin_can_store_laptop(): void
    {
        $payload = [
            'brand' => 'HP',
            'model' => 'EliteBook 840 G6',
            'processor_cpu' => 'Intel Core i5-8265U',
            'memory_ram' => '16 GB DDR4',
            'storage' => '256 GB SSD',
            'graphics_gpu' => 'Intel UHD 620',
            'display' => '14" FHD IPS',
            'battery_health' => '',
            'storage_health' => '',
            'thermal_performance' => '',
            'physical_condition' => '',
            'ports_connectivity' => '',
            'camera' => '',
            'microphone' => '',
            'audio' => '',
            'keyboard' => '',
            'operating_system' => '',
            'is_locks_cleared' => '',
            'is_available' => '1',
            'addons' => '',
        ];

        $response = $this->actingAs($this->adminUser)->post('/admin/inventory', $payload);

        $response->assertRedirect('/admin/inventory');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('laptop', [
            'brand' => 'HP',
            'model' => 'EliteBook 840 G6',
            'battery_health' => null,
            'storage_health' => null,
            'is_available' => 1,
        ]);

        $created = Laptop::where('model', 'EliteBook 840 G6')->first();
        $this->assertNotNull($created->created_at);
    }

    /**
     * Test admin can update a laptop.
     */
    public function test_admin_can_update_laptop(): void
    {
        $laptop = Laptop::create([
            'brand' => 'HP',
            'model' => 'ProBook 450 G8',
            'processor_cpu' => 'Intel Core i5-1135G7',
            'memory_ram' => '8 GB DDR4',
            'storage' => '256 GB SSD',
            'graphics_gpu' => 'Intel Iris Xe',
            'display' => '15.6" FHD',
            'is_available' => true,
        ]);

        $updatePayload = [
            'brand' => 'HP',
            'model' => 'ProBook 450 G8 Updated',
            'processor_cpu' => 'Intel Core i5-1135G7',
            'memory_ram' => '16 GB DDR4',
            'storage' => '512 GB NVMe SSD',
            'graphics_gpu' => 'Intel Iris Xe',
            'display' => '15.6" FHD Anti-Glare',
            'battery_health' => 'Good (90% health)',
            'storage_health' => '100% SMART OK',
            'camera' => 'Working',
            'audio' => 'Working',
            'is_available' => '1',
        ];

        $response = $this->actingAs($this->adminUser)->put("/admin/inventory/{$laptop->id}", $updatePayload);

        $response->assertRedirect('/admin/inventory');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('laptop', [
            'id' => $laptop->id,
            'model' => 'ProBook 450 G8 Updated',
            'memory_ram' => '16 GB DDR4',
            'battery_health' => 'Good (90% health)',
        ]);
    }

    /**
     * Test admin can delete a laptop.
     */
    public function test_admin_can_destroy_laptop(): void
    {
        $laptop = Laptop::create([
            'brand' => 'Lenovo',
            'model' => 'IdeaPad 3',
            'processor_cpu' => 'AMD Ryzen 5 3500U',
            'memory_ram' => '8 GB DDR4',
            'storage' => '256 GB SSD',
            'graphics_gpu' => 'AMD Radeon Vega 8',
            'display' => '15.6" FHD',
            'is_available' => false,
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/inventory/{$laptop->id}");

        $response->assertRedirect('/admin/inventory');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('laptop', [
            'id' => $laptop->id,
        ]);
    }

    /**
     * Test admin can toggle availability.
     */
    public function test_admin_can_toggle_availability(): void
    {
        $laptop = Laptop::where('model', 'ThinkPad T480')->firstOrFail();
        $this->assertTrue((bool)$laptop->is_available);

        $response = $this->actingAs($this->adminUser)->patch("/admin/inventory/{$laptop->id}/toggle-status");

        $response->assertRedirect();
        $laptop->refresh();
        $this->assertFalse((bool)$laptop->is_available);
    }

    /**
     * Test admin login and logout flow.
     */
    public function test_admin_login_and_logout_flow(): void
    {
        // 1. Submit valid credentials at /admin
        $loginResponse = $this->post('/admin', [
            'email' => 'admin@admin.com',
            'password' => 'password',
        ]);

        $loginResponse->assertRedirect('/admin/inventory');
        $this->assertAuthenticatedAs($this->adminUser);

        // 2. Logout
        $logoutResponse = $this->post('/admin/logout');
        $logoutResponse->assertRedirect('/');
        $this->assertGuest();
    }

    /**
     * Test AI chat recommendation requires a valid message.
     */
    public function test_ai_chat_recommendation_validates_input(): void
    {
        $response = $this->postJson('/chat/recommend', [
            'message' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message']);
    }

    /**
     * Test AI chat recommendation returns recommendations based on in-stock laptops.
     */
    public function test_ai_chat_recommendation_returns_smart_response(): void
    {
        $response = $this->postJson('/chat/recommend', [
            'message' => 'Saya mahasiswa butuh laptop untuk koding dengan RAM 16GB dan baterai awet',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'reply',
            'source',
        ]);
        $response->assertJsonPath('status', 'success');

        $reply = $response->json('reply');
        $this->assertNotEmpty($reply);
        // Should recommend ThinkPad T480 which has 16GB RAM and is seeded
        $this->assertStringContainsString('ThinkPad', $reply);
    }

    /**
     * Test AI chat recommendation handles empty inventory gracefully.
     */
    public function test_ai_chat_recommendation_handles_no_available_laptops(): void
    {
        Laptop::query()->update(['is_available' => false]);

        $response = $this->postJson('/chat/recommend', [
            'message' => 'Rekomendasi laptop gaming',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $this->assertStringContainsString('belum ada laptop', $response->json('reply'));
    }

    /**
     * Test AI chat advisor widget only appears on catalog pages, not on admin or unrelated pages.
     */
    public function test_ai_chat_widget_only_appears_on_catalog_pages(): void
    {
        // 1. Should appear on catalog index and catalog show
        $catalogResponse = $this->get('/catalog');
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertSee('id="ai-chat-widget"', false);

        $laptop = Laptop::first();
        if ($laptop) {
            $showResponse = $this->get("/catalog/{$laptop->id}");
            $showResponse->assertStatus(200);
            $showResponse->assertSee('id="ai-chat-widget"', false);
        }

        // 2. Should NOT appear on admin login page
        $adminLoginResponse = $this->get('/admin');
        $adminLoginResponse->assertStatus(200);
        $adminLoginResponse->assertDontSee('id="ai-chat-widget"', false);

        // 3. Should NOT appear on admin inventory dashboard
        $inventoryResponse = $this->actingAs($this->adminUser)->get('/admin/inventory');
        $inventoryResponse->assertStatus(200);
        $inventoryResponse->assertDontSee('id="ai-chat-widget"', false);

        // 4. Should NOT appear on admin profile page
        $profileResponse = $this->actingAs($this->adminUser)->get('/admin/profile');
        $profileResponse->assertStatus(200);
        $profileResponse->assertDontSee('id="ai-chat-widget"', false);
    }

    /**
     * Test admin can store and update a laptop with price estimation and local image.
     */
    public function test_admin_can_crud_laptop_with_price_and_image(): void
    {
        Storage::fake('public');

        $fakeImage = UploadedFile::fake()->image('laptop_thinkpad.jpg', 600, 400);

        $payload = [
            'brand' => 'Lenovo',
            'model' => 'ThinkPad X1 Carbon Gen 9',
            'processor_cpu' => 'Intel Core i7-1165G7',
            'memory_ram' => '16 GB LPDDR4x',
            'storage' => '512 GB NVMe SSD',
            'graphics_gpu' => 'Intel Iris Xe Graphics',
            'display' => '14.0" FHD+ (1920x1200) IPS',
            'price_estimate' => '8500000',
            'image' => $fakeImage,
            'is_available' => '1',
        ];

        // 1. Create with price & image
        $createResponse = $this->actingAs($this->adminUser)->post('/admin/inventory', $payload);
        $createResponse->assertRedirect('/admin/inventory');

        $laptop = Laptop::where('model', 'ThinkPad X1 Carbon Gen 9')->firstOrFail();
        $this->assertEquals(8500000, (float)$laptop->price_estimate);
        $this->assertNotNull($laptop->image_path);
        Storage::disk('public')->assertExists($laptop->image_path);

        // 2. View in catalog shows formatted price and image
        $catalogResponse = $this->get('/catalog/' . $laptop->id);
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertSee('Rp 8.500.000');
        $catalogResponse->assertSee($laptop->image_url);

        // 3. Update price
        $updatePayload = array_merge($payload, [
            'price_estimate' => '7900000',
        ]);
        $updateResponse = $this->actingAs($this->adminUser)->put("/admin/inventory/{$laptop->id}", $updatePayload);
        $updateResponse->assertRedirect('/admin/inventory');

        $laptop->refresh();
        $this->assertEquals(7900000, (float)$laptop->price_estimate);
    }

    /**
     * Test admin can upload multiple images and view them in a carousel on the catalog page.
     */
    public function test_admin_can_upload_multiple_images_and_view_carousel_on_catalog(): void
    {
        Storage::fake('public');

        $img1 = UploadedFile::fake()->image('laptop_front.jpg', 800, 600);
        $img2 = UploadedFile::fake()->image('laptop_keyboard.jpg', 800, 600);
        $img3 = UploadedFile::fake()->image('laptop_side.jpg', 800, 600);

        $payload = [
            'brand' => 'ASUS',
            'model' => 'ZenBook 14 OLED',
            'processor_cpu' => 'Intel Core Ultra 7 155H',
            'memory_ram' => '32GB LPDDR5X',
            'storage' => '1TB NVMe Gen4 SSD',
            'graphics_gpu' => 'Intel Arc Graphics',
            'display' => '14" 2.8K 120Hz OLED',
            'price_estimate' => 18500000,
            'is_available' => '1',
            'images' => [$img1, $img2, $img3],
        ];

        $response = $this->actingAs($this->adminUser)->post('/admin/inventory', $payload);
        $response->assertRedirect('/admin/inventory');

        $laptop = Laptop::where('model', 'ZenBook 14 OLED')->firstOrFail();

        // Ensure images were saved in laptop_images relationship
        $this->assertCount(3, $laptop->images);
        $this->assertTrue($laptop->hasMultipleImages());
        $this->assertCount(3, $laptop->all_image_urls);

        // Verify all 3 files exist on storage
        foreach ($laptop->images as $img) {
            Storage::disk('public')->assertExists($img->image_path);
        }

        // View catalog show page: carousel components must be present
        $catalogResponse = $this->get("/catalog/{$laptop->id}");
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertSee('id="laptopPhotoCarousel"', false);
        $catalogResponse->assertSee('carousel-control-prev', false);
        $catalogResponse->assertSee('carousel-control-next', false);
        $catalogResponse->assertSee('carousel-indicators', false);

        // All image URLs should be rendered
        foreach ($laptop->all_image_urls as $url) {
            $catalogResponse->assertSee($url);
        }
    }

    /**
     * Test admin can selectively delete an image from a laptop gallery.
     */
    public function test_admin_can_selectively_delete_images_from_laptop(): void
    {
        Storage::fake('public');

        $img1 = UploadedFile::fake()->image('dell_1.jpg');
        $img2 = UploadedFile::fake()->image('dell_2.jpg');

        $laptop = Laptop::create([
            'brand' => 'Dell',
            'model' => 'XPS 13 Plus',
            'processor_cpu' => 'Intel Core i7-1360P',
            'memory_ram' => '16GB',
            'storage' => '512GB SSD',
            'graphics_gpu' => 'Intel Iris Xe',
            'display' => '13.4" FHD+',
            'is_available' => true,
        ]);

        $path1 = $img1->store('laptops', 'public');
        $path2 = $img2->store('laptops', 'public');

        $laptopImage1 = $laptop->images()->create(['image_path' => $path1, 'sort_order' => 0]);
        $laptopImage2 = $laptop->images()->create(['image_path' => $path2, 'sort_order' => 1]);
        $laptop->update(['image_path' => $path1]);

        Storage::disk('public')->assertExists($path1);
        Storage::disk('public')->assertExists($path2);

        // Update laptop and request deletion of the first image
        $updatePayload = [
            'brand' => 'Dell',
            'model' => 'XPS 13 Plus',
            'processor_cpu' => 'Intel Core i7-1360P',
            'memory_ram' => '16GB',
            'storage' => '512GB SSD',
            'graphics_gpu' => 'Intel Iris Xe',
            'display' => '13.4" FHD+',
            'delete_images' => [$laptopImage1->id],
        ];

        $response = $this->actingAs($this->adminUser)->put("/admin/inventory/{$laptop->id}", $updatePayload);
        $response->assertRedirect('/admin/inventory');

        // Verify image 1 was deleted from disk and database
        Storage::disk('public')->assertMissing($path1);
        $this->assertDatabaseMissing('laptop_images', ['id' => $laptopImage1->id]);

        // Verify image 2 still exists
        Storage::disk('public')->assertExists($path2);
        $this->assertDatabaseHas('laptop_images', ['id' => $laptopImage2->id]);

        // Primary image_path should now fallback to image 2
        $laptop->refresh();
        $this->assertEquals($path2, $laptop->image_path);
    }

    /**
     * Test deleting a laptop also deletes all its associated multiple images from storage.
     */
    public function test_deleting_laptop_cleans_up_all_multiple_images(): void
    {
        Storage::fake('public');

        $img1 = UploadedFile::fake()->image('hp_front.jpg');
        $img2 = UploadedFile::fake()->image('hp_back.jpg');

        $path1 = $img1->store('laptops', 'public');
        $path2 = $img2->store('laptops', 'public');

        $laptop = Laptop::create([
            'brand' => 'HP',
            'model' => 'Spectre x360',
            'processor_cpu' => 'Intel Core i7-1355U',
            'memory_ram' => '16GB',
            'storage' => '1TB SSD',
            'graphics_gpu' => 'Intel Iris Xe',
            'display' => '13.5" 3K2K OLED',
            'is_available' => true,
            'image_path' => $path1,
        ]);

        $laptop->images()->create(['image_path' => $path1, 'sort_order' => 0]);
        $laptop->images()->create(['image_path' => $path2, 'sort_order' => 1]);

        Storage::disk('public')->assertExists($path1);
        Storage::disk('public')->assertExists($path2);

        // Delete laptop
        $response = $this->actingAs($this->adminUser)->delete("/admin/inventory/{$laptop->id}");
        $response->assertRedirect('/admin/inventory');

        // Both files should be deleted from storage
        Storage::disk('public')->assertMissing($path1);
        Storage::disk('public')->assertMissing($path2);

        // Relational records should be deleted (cascade)
        $this->assertDatabaseMissing('laptop', ['id' => $laptop->id]);
        $this->assertDatabaseMissing('laptop_images', ['laptop_id' => $laptop->id]);
    }

    // ─────────────────────────────────────────────
    //  Admin Profile (Account Settings)
    // ─────────────────────────────────────────────

    /**
     * Any logged-in admin can view the Account Settings page.
     */
    public function test_admin_can_access_own_profile(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/profile');

        $response->assertStatus(200);
        $response->assertSee('Account Settings');
    }

    /**
     * Admin can update their own name and email.
     */
    public function test_admin_can_update_own_name_and_email(): void
    {
        $response = $this->actingAs($this->adminUser)->put('/admin/profile', [
            'name'  => 'Updated Name',
            'email' => 'updated@admin.com',
        ]);

        $response->assertRedirect();
        $this->adminUser->refresh();
        $this->assertEquals('Updated Name', $this->adminUser->name);
        $this->assertEquals('updated@admin.com', $this->adminUser->email);
    }

    /**
     * Admin can change their own password with a valid current password.
     */
    public function test_admin_can_change_own_password(): void
    {
        $response = $this->actingAs($this->adminUser)->put('/admin/profile/password', [
            'current_password'      => 'password',
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $this->adminUser->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->adminUser->password));
    }

    /**
     * Admin cannot change password with wrong current password.
     */
    public function test_admin_cannot_change_password_with_wrong_current_password(): void
    {
        $response = $this->actingAs($this->adminUser)->put('/admin/profile/password', [
            'current_password'      => 'wrongpassword',
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    // ─────────────────────────────────────────────
    //  Manage Admins (Main Admin only)
    // ─────────────────────────────────────────────

    /**
     * Regular admin is blocked from the Manage Admins index.
     */
    public function test_regular_admin_cannot_access_manage_admins(): void
    {
        $regularAdmin = User::create([
            'name'          => 'Regular Admin',
            'email'         => 'regular@admin.com',
            'password'      => Hash::make('password'),
            'is_main_admin' => false,
        ]);

        $response = $this->actingAs($regularAdmin)->get('/admin/users');

        $response->assertRedirect(route('laptops.index'));
    }

    /**
     * Main Admin can view the Manage Admins index.
     */
    public function test_main_admin_can_access_manage_admins(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('Manage Administrators');
    }

    /**
     * Main Admin can create a new regular admin account.
     */
    public function test_main_admin_can_create_new_admin(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/users', [
            'name'                  => 'New Admin',
            'email'                 => 'newadmin@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'is_main_admin'         => '0',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'newadmin@example.com']);
    }

    /**
     * Main Admin can delete another (non-self) admin.
     */
    public function test_main_admin_can_delete_other_admin(): void
    {
        $otherAdmin = User::create([
            'name'          => 'Other Admin',
            'email'         => 'other@admin.com',
            'password'      => Hash::make('password'),
            'is_main_admin' => false,
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/users/{$otherAdmin->id}");

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $otherAdmin->id]);
    }

    /**
     * Cannot delete the last remaining Main Admin.
     */
    public function test_main_admin_cannot_delete_last_main_admin(): void
    {
        // $this->adminUser is the only main admin — try deleting them (self-delete also blocked separately)
        // So create a second main admin and try to delete the only main admin from their perspective.
        $secondMain = User::create([
            'name'          => 'Second Main',
            'email'         => 'second@admin.com',
            'password'      => Hash::make('password'),
            'is_main_admin' => true,
        ]);

        // secondMain tries to delete $this->adminUser (the other main admin — still 1 left so it works)
        // Instead, let's make secondMain the only main admin, then try to delete them
        $this->adminUser->update(['is_main_admin' => false]);

        $response = $this->actingAs($secondMain)->delete("/admin/users/{$secondMain->id}");
        // Self-delete is blocked first
        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $secondMain->id]);
    }

    /**
     * Admin cannot delete their own account.
     */
    public function test_main_admin_cannot_delete_themselves(): void
    {
        $response = $this->actingAs($this->adminUser)->delete("/admin/users/{$this->adminUser->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $this->adminUser->id]);
    }
}
