<?php

namespace Tests\Feature\Models\Client;

use App\Models\Client;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\BaseTestCase;

class ReadTest extends BaseTestCase
{
    #[Test]
    public function success(): void
    {
        $user = $this->makeActingAsUser();

        $response = $this->actingAs($user)
            ->get('/api/clients');

        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function unauthorized(): void
    {
        $response = $this->get('/api/clients');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    #[Test]
    public function check_json_structure(): void
    {
        $user = $this->makeActingAsUser();

        Client::factory()->create([
            'date' => '2000.01.01',
        ]);

        Client::factory()->create([
            'date' => '2000.01.01',
        ]);

        $response = $this->actingAs($user)->get('/api/clients?groupBy=date');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                '2000-01-01' => [

                ],
            ],
        ]);
    }
}
