<?php

namespace Project\TransactionModule\Mappers;

use Project\TransactionModule\Contexts\TransactionStatus;
use Project\TransactionModule\DTOs\TransactionDTO;
use Project\TransactionModule\Models\Transaction;

class TransactionDTOMapper
{
    protected TransactionDTO $transactionDTO;
    protected Transaction $transaction;

    /**
     * @param Transaction $transaction
     * @return TransactionDTOMapper
     */
    public function setTransaction(Transaction $transaction): TransactionDTOMapper
    {
        $this->transaction = $transaction;
        $this->transactionDTO = new TransactionDTO();
        return $this;
    }


    public function map(): static
    {
        $this->transactionDTO->setAmount($this->transaction->amount);
        $this->transactionDTO->setCurrency($this->transaction->currency);
        $this->transactionDTO->setStatus(TransactionStatus::get($this->transaction->status));
        $this->transactionDTO->setEmail($this->transaction->email);
        $this->transactionDTO->setTransactionDate($this->transaction->transaction_date->setTimezone('UTC')->format('Y-m-d H:i:s'));
        $this->transactionDTO->setProvider($this->transaction->provider);
        $this->transactionDTO->setProviderId($this->transaction->provider_id);

        return $this;
    }

    public function get(): TransactionDTO
    {
        return $this->transactionDTO;
    }
}
