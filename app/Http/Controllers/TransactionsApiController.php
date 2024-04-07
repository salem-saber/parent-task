<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Project\Base\ResponseBuilder;
use Project\Base\Traits\HandelServiceResponse;
use Project\TransactionModule\Requests\GetTransactionsRequest;
use Project\TransactionModule\Services\TransactionsService;

class TransactionsApiController extends Controller
{
    use HandelServiceResponse;

    protected ResponseBuilder $responseBuilder;
    private TransactionsService $transactionsService;


    public function __construct(TransactionsService $transactionsService)
    {
        $this->responseBuilder = new ResponseBuilder();
        $this->transactionsService = $transactionsService;

    }

    public function getTransactions(GetTransactionsRequest $request): JsonResponse
    {
        $requestData = $request->all();
        $data = $this->transactionsService->getTransactions(
            statusCode: $requestData['statusCode'] ?? '',
            currency: $requestData['currency'] ?? '',
            provider: $requestData['provider'] ?? '',
            balanceMin: $requestData['balanceMin'] ?? null,
            balanceMax: $requestData['balanceMax'] ?? null,
            dateFrom: $requestData['dateFrom'] ?? '',
            dateTo: $requestData['dateTo'] ?? '',
            pageSize: $requestData['pageSize'] ?? 10,
            pageNo: $requestData['pageNo'] ?? 1,
        );

        return $this->getServiceResponse($this->transactionsService, $data);
    }

}
