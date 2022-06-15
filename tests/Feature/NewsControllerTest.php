<?php

namespace Tests\Feature;

use App\Events\NewsCreated;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class NewsControllerTest extends TestCase
{
    use WithFaker;
    /**
     * testing the index.
     *
     * @return void
     */
    public function test_news_index_successful()
    {
        $user = User::factory()->create();
 
        $response = $this->actingAs($user)->get('news');

        $response->assertStatus(200)
            ->assertJson(['message' => 'All News Successfully Retrived']);
    }

    /**
     * testing the read.
     *
     * @return void
     */
    public function test_news_details_successful()
    {
        
        $user = User::factory()->create();
        $news = News::factory()->create(['user_id' => $user->id]);
    
        $response = $this->actingAs($user)->get("news/".$news->id);

        $response->assertStatus(200)
            ->assertJson(['message' => 'News Record Successfully Retrived']);    
    }

    /**
     * testing the read unsuccessful returning 404.
     *
     * @return void
     */
    public function test_news_details_failed_if_it_does_not_exist()
    {
        $user = User::factory()->create();
        $news = News::factory()->create(['user_id' => $user->id]);
    
        $response = $this->actingAs($user)->get("news/0");

        $response->assertStatus(404)
            ->assertJson(['message' => 'News Record does not exist']); 
    }

    /**
     * testing the create (store) endpoint.
     *
     * @return void
     */
    public function test_news_create_is_successful()
    {
        Event::fake();
        $user = User::factory()->create();
        $data = [
            'title'=> $this->faker->sentence(),
            'body'=> $this->faker->text()
        ];
    
        $response = $this->actingAs($user)->post("news", $data);

        $response->assertStatus(201)
            ->assertJson(['message' => 'News Record Successfully Created']); 
    }

    /**
     * testing the update  endpoint.
     *
     * @return void
     */
    public function test_news_update_is_successful()
    {
        $user = User::factory()->create();
        $news = News::factory()->create(['user_id' => $user->id]);
        $data = [
            'title'=> 'Title was updated',
            'body'=> $this->faker->text()
        ];
    
        $response = $this->actingAs($user)->put("news/".$news->id, $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'News Record Successfully Updated']); 
        
        $this->assertDatabaseHas('news', [
            'title' => 'Title was updated',
        ]);
    }

    /**
     * testing the delete (destroy)  endpoint.
     *
     * @return void
     */
    public function test_news_delete_is_successful()
    {
        $user = User::factory()->create();
        $news = News::factory()->create([
            'user_id' => $user->id, 
            'title'=> 'Title to be deleted for Testing'
        ]);
        $response = $this->actingAs($user)->delete("news/".$news->id."/delete");

        $response->assertStatus(200)
            ->assertJson(['message' => 'News Record Successfully deleted']);
        
        $this->assertDatabaseMissing('news', [
            'title'=> 'Title to be deleted for Testing'
        ]);
    }
}
