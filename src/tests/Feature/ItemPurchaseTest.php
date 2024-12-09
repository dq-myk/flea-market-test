<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ItemPurchaseTest extends TestCase
{
    use RefreshDatabase;

    private function test_create_user(): User
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        return $user;
    }

    private function test_create_item(): Item
    {
        return Item::create([
            'name' => 'HDD',
            'brand' => 'Buffalo',
            'detail' => '高速で信頼性の高いハードディスク',
            'image_path' => 'storage/images/HDD+Hard+Disk.jpg',
            'price' => 5000,
            'color' => '黒',
            'condition' => '目立った傷や汚れなし',
            'status' => '新品',
            'status_comment' => '商品の状態は良好です。目立った傷や汚れもありません。',
        ]);
    }

    //商品購入処理
    public function test_purchase()
    {
        $user = $this->test_create_user();
        $item = $this->test_create_item();

        $user->purchases()->create(['item_id' => $item->id]);

        $response = $this->withoutMiddleware()->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $purchaseData = [
            'payment_method' => 'card',
            'item_id' => $item->id,
        ];

        $response = $this->post("/purchase/{item_id}/complete", $purchaseData);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    //購入済商品Sold表示
    public function test_purchase_sold()
    {
        $user = $this->test_create_user();
        $item = $this->test_create_item();

        $user->purchases()->create(['item_id' => $item->id]);

        $response = $this->withoutMiddleware()->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $purchaseData = [
            'payment_method' => 'card',
            'item_id' => $item->id,
        ];

        $response = $this->post("/purchase/{item_id}/complete", $purchaseData);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertSee($item->image_path);
        $response->assertSee($item->name);
        $response->assertSeeText('Sold');
    }

    //購入済商品プロフィール画面表示
    public function test_purchase_profile()
    {
        $user = $this->test_create_user();
        $item = $this->test_create_item();

        $user->purchases()->create(['item_id' => $item->id]);

        $response = $this->withoutMiddleware()->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $purchaseData = [
            'payment_method' => 'card',
            'item_id' => $item->id,
        ];

        $response = $this->post("/purchase/{item_id}/complete", $purchaseData);

        $response = $this->actingAs($user)->get('/mypage?tab=buy');
        $response->assertStatus(200);
        $response->assertSee($item->image_path);
        $response->assertSee($item->name);
    }
}
