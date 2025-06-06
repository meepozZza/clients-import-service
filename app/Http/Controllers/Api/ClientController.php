<?php

namespace App\Http\Controllers\Api;

ini_set('max_execution_time', 600);
ini_set('memory_limit', '256M');

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Services\Usecases\ClientImportUsecase;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Reader\Exception\ReaderNotOpenedException;
use Throwable;

class ClientController extends Controller
{
    use AuthorizesRequests;

    /**
     * @throws AuthorizationException
     */
    public function index(Request $request, ClientRepository $repository): AnonymousResourceCollection
    {
        $this->authorize('index', Client::class);

        return ClientResource::collection(
            $repository->get()
                ->when($request->has('groupBy'), fn ($r) => $r->groupBy($request->get('groupBy'))),
        );
    }

    /**
     * @throws IOException
     * @throws AuthorizationException
     * @throws ReaderNotOpenedException
     * @throws Throwable
     */
    public function import(
        ImportClientRequest $request,
        ClientImportUsecase $clientImportUsecase
    ): JsonResponse {
        $this->authorize('import', Client::class);

        $clientImportUsecase->import($request->file('file'));

        return response()->json([
            'data' => $clientImportUsecase->importService()->getImportedData(),
            'errors' => $clientImportUsecase->importService()->getErrors(),
        ]);
    }
}
