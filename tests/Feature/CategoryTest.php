<?php

use function Pest\Laravel\get;

describe("Category Management", function() {
    
    // test('can see category list', function () {
    //     $response = get('/api/categories');
        
    //     $response->assertStatus(200);
    //     $response->assertJsonStructure([
    //         'status',
    //         'data' => [
    //             '*' => [
    //                 'id',
    //                 'name',
    //             ]
    //         ]
    //     ]);
    // });
    
    // test('can create a new category', function () {
    //     $response = $this->postJson('/api/categories', [
    //         'name' => 'Nouvelle Catégorie',
    //         'parent_id' => null
    //     ]);
        
    //     $response->assertStatus(201);
    //     $response->assertJsonStructure([
    //         'status',
    //         'message',
    //         'data' => [
    //             'id',
    //             'name',
    //             'parent_id'
    //         ]
    //     ]);
        
    //     $this->assertDatabaseHas('categories', [
    //         'name' => 'Nouvelle Catégorie'
    //     ]);
    // });
    
    // test('can view a single category', function () {
    //     // Create
    //     $category = \App\Models\Category::factory()->create();
        
    //     $response = get("/api/categories/{$category->id}");
        
    //     $response->assertStatus(200);
    //     $response->assertJsonStructure([
    //         'status',
    //         'data' => [
    //             'id',
    //             'name'
    //         ]
    //     ]);
    //     $response->assertJsonPath('data.id', $category->id);
    //     $response->assertJsonPath('data.name', $category->name);
    // });
    
    test('can update a category', function () {
        // Create
        $category = \App\Models\Category::factory()->create();
        
        $response = $this->putJson("/api/categories/{$category->id}", [
            'name' => 'Catégorie Modifiée'
        ]);
        
        $response->assertStatus(200);
        $response->assertJsonPath('data.name', 'Catégorie Modifiée');
        
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Catégorie Modifiée'
        ]);
    });
    
    // test('can delete a category', function () {
    //     // Create
    //     $category = \App\Models\Category::factory()->create();
        
    //     $response = $this->deleteJson("/api/categories/{$category->id}");
        
    //     $response->assertStatus(200);
    //     $response->assertJsonPath('status', 'success');
        
    //     $this->assertSoftDeleted('categories', [
    //         'id' => $category->id
    //     ]);
    // });
});