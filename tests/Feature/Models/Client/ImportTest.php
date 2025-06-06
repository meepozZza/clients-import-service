<?php

namespace Tests\Feature\Models\Client;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\BaseTestCase;

class ImportTest extends BaseTestCase
{
    #[Test]
    public function success(): void
    {
        $user = $this->makeActingAsUser();

        Storage::fake();

        $file = UploadedFile::fake()->createWithContent('clients.xlsx', file_get_contents(base_path('tests/resources/clients.xlsx')));

        $response = $this->actingAs($user, 'api')
            ->post('/api/clients/import', [
                'file' => $file,
            ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function success_for_million_rows(): void
    {
        $user = $this->makeActingAsUser();

        Storage::fake();

        $file = UploadedFile::fake()->createWithContent('clients_million.xlsx', file_get_contents(base_path('tests/resources/clients.xlsx')));

        $response = $this->actingAs($user, 'api')
            ->post('/api/clients/import', [
                'file' => $file,
            ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function unauthorized(): void
    {
        $response = $this->post('/api/clients/import');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
