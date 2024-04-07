<?php

namespace Project\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Project\TransactionModule\Contexts\TransactionStatus;
use Project\TransactionModule\Repositories\TransactionRepository;
use pcrov\JsonReader\JsonReader;

class InsertProviderData extends Command
{
    protected JsonReader $jsonReader;
    protected TransactionRepository $transactionRepository;


    protected $signature = 'insert:provider-data';

    protected $description = 'Import user and transactions data from json to database';

    public function __construct()
    {
        parent::__construct();
        $this->jsonReader = new JsonReader();
        $this->transactionRepository = new TransactionRepository();
    }


    public function handle(): void
    {
        $this->info('Start importing data from json files to database');
        $this->importTransactions();
    }

    public function importTransactions()
    {

        $this->dataProviderX();
        $this->providerDataY();
    }


    public function dataProviderX()
    {
        $this->info('transactions Data Provider X file...');
        if (!Storage::disk('provider')->exists('DataProviderX.json')) {
            $this->error('transactions : ' . __('file.not_found'));
            return;
        }
        $path = storage_path('provider/DataProviderX.json');
        $this->jsonReader->open($path);
        $depth = $this->jsonReader->depth(); // Check in a moment to break when the array is done.
        $this->jsonReader->read(); // Step to the first object.
        do {
            $data = $this->jsonReader->value();
            $this->withProgressBar($data['transactions'], function ($transaction) {
                $this->performImportingTransactionsDataProviderX($transaction);
            });

        } while ($this->jsonReader->next() && $this->jsonReader->depth() > $depth); // Read each sibling.

        $this->jsonReader->close();
    }

    public function dataProviderXStatus($status): string
    {
        return match ($status) {
            1 => TransactionStatus::AUTHORISED,
            2 => TransactionStatus::DECLINE,
            3 => TransactionStatus::REFUNDED
        };
    }
    public function performImportingTransactionsDataProviderX($data)
    {
        $this->transactionRepository->create([
            'amount' => $data['parentAmount'],
            'email' => $data['parentEmail'],
            'currency' => $data['Currency'],
            'status' => $this->dataProviderXStatus($data['statusCode']),
            'transaction_date' => $data['registerationDate'],
            'provider' => 'DataProviderX',
            'provider_id' => $data['parentIdentification'],
        ]);
    }

    public function providerDataY()
    {
        $this->info('transactions Data Provider Y file...');
        if (!Storage::disk('provider')->exists('DataProviderY.json')) {
            $this->error('transactions : ' . __('file.not_found'));
            return;
        }
        $path = storage_path('provider/DataProviderY.json');
        $this->jsonReader->open($path);
        $depth = $this->jsonReader->depth(); // Check in a moment to break when the array is done.
        $this->jsonReader->read(); // Step to the first object.
        do {
            $data = $this->jsonReader->value();
            $this->withProgressBar($data['transactions'], function ($transaction) {
                $this->performImportingTransactionsDataProviderY($transaction);
            });

        } while ($this->jsonReader->next() && $this->jsonReader->depth() > $depth); // Read each sibling.

        $this->jsonReader->close();
    }

    public function dataProviderYStatus($status): string
    {
        return match ($status) {
            100 => TransactionStatus::AUTHORISED,
            200 => TransactionStatus::DECLINE,
            300 => TransactionStatus::REFUNDED
        };
    }

    public function performImportingTransactionsDataProviderY($data)
    {
        $this->transactionRepository->create([
            'amount' => $data['balance'],
            'email' => $data['email'],
            'currency' => $data['currency'],
            'status' => $this->dataProviderYStatus($data['status']),
            'transaction_date' => Carbon::createFromFormat('d/m/Y',$data['created_at'])->format('Y-m-d'),
            'provider' => 'DataProviderY',
            'provider_id' => $data['id'],
        ]);
    }
}

