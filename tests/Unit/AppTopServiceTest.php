<?php

namespace Tests\Unit;

use Illuminate\Support\Carbon;
use Tests\TestCase;
use App\Services\AppTopService;
use Illuminate\Support\Facades\Cache;

class AppTopServiceTest extends TestCase
{
    private $appTopService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->appTopService = new AppTopService();
    }

    public function testGetTopByDateReturnsValidResponse()
    {
        $date = Carbon::now()->subDays(3)->format('Y-m-d');

        $fakeResponse = response()->json(['status_code' => 200, 'message' => 'ok', 'data' => ['category1' => 1, 'category2' => 2]]);

        Cache::shouldReceive('remember')
            ->once()
            ->with("app_top:{$date}", \Mockery::any(), \Mockery::any())
            ->andReturn($fakeResponse);

        $response = $this->appTopService->getTopByDate($date);

        // Проверяем ответ
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('category1', $response->getContent());
    }
}

