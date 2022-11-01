<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TopControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * 報告・未報告の判定が正しいか
     *
     * @return void
     */
    public function test_reported()
    {
        $this->seed();

        $user = User::find(1);
        $res = $this->actingAs($user)->get(route('api_attendance_record'));
        $res->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['attendanceStatus' => 1]);
    }
}