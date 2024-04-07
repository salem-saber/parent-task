<?php

namespace Project\TransactionModule\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use Project\Base\Repository;
use Project\TransactionModule\Models\Transaction;

class TransactionRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(new Transaction());
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

        return $this->getModel()->query()->where(function ($query)
        use ($statusCode, $currency, $provider, $balanceMin, $balanceMax, $dateFrom, $dateTo) {
            if ($statusCode) {
                $query->where('status', $statusCode);
            }
            if ($currency) {
                $query->where('currency', $currency);
            }
            if ($provider) {
                $query->where('provider', $provider);
            }
            if ($balanceMin) {
                $query->where('amount', '>=', $balanceMin);
            }
            if ($balanceMax) {
                $query->where('amount', '<=', $balanceMax);
            }
            if ($dateFrom) {
                $query->whereDate('transaction_date', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('transaction_date', '<=', $dateTo);
            }
        })->simplePaginate($pageSize, ['*'], 'page', $pageNo);
    }
}
