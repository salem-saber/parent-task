<?php

namespace Project\TransactionModule\Services;

use Project\Base\Service;
use Project\TransactionModule\Mappers\TransactionDTOMapper;
use Project\TransactionModule\Repositories\TransactionRepository;
use Illuminate\Contracts\Pagination\Paginator;

class TransactionsService extends Service
{
    private TransactionRepository $transactionRepository;

    public function __construct()
    {
        $this->transactionRepository = new TransactionRepository();
    }


    public function getTransactions(
        string $statusCode = '',
        string $currency = '',
        string $provider = '',
        ?float $balanceMin = null,
        ?float $balanceMax = null,
        string $dateFrom = '',
        string $dateTo = '',
        int    $pageSize = 10,
        int    $pageNo = 1,
    ): ?Paginator
    {
        $statusCode = strtoupper($statusCode);
        $currency = strtoupper($currency);

        $data = $this->transactionRepository->getTransactions(
            $statusCode,
            $currency,
            $provider,
            $balanceMin,
            $balanceMax,
            $dateFrom,
            $dateTo,
            $pageSize,
            $pageNo
        );

        // map users to produce clean API response
        $collection = $data->getCollection();
        $mappedData = $collection->map(function ($transaction) {
            $userDTOMapper = new TransactionDTOMapper();
            return $userDTOMapper->setTransaction($transaction)->map()->get();
        });
        $data->setCollection($mappedData);
        return $data;
    }
}
