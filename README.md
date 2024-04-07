# Collect data from multi providers
___
## Description
We have three providers that make transfers using a phone number. We need to read and create some filters on them to get the aspired results.

#### Jsons files
1. DataW.json
2. DataX.json
3. DataY.json



#### Acceptance Criteria
**Implement this APIs should contain :**
* Using PHP Laravel framework
* Implement API endpoint to get all transactions
* It should list all transactions from all providers (W, X, Y)
* It should be able to filter results by status (paid, pending, reject)
* It should be able to filter results by currency

---
## Installation

1. Clone the repo
```sh
git clone https://github.com/salem-saber/parent-task.git
```
2. Install Composer packages
```sh
composer install
```
3. Copy `.env.example` to `.env` and set your database credentials
4. Generate an app encryption key
```sh
php artisan key:generate
```
5. Migrate the database
```sh
php artisan migrate
```
6. Put json files in `//storage/provider` folder
   1. DataProviderX.json
   2. DataProviderY.json


7. Run the command to insert the data from json files to database
```sh
php artisan insert:provider-data
```
other way : this command line is run in background daily with skip the exists data.

8. Run the server
```sh
php artisan serve
```
___

## Code Architecture 
**ONION Architecture**

is used to make the code more readable and maintainable. 

App folder located in `//app` directory with name `Project`
* **Project** folder contains one folder for each module in the app.

**BASE**
* `Repository` : is used to implement the main methods of the repository.
* `Service` : is used to implement the main methods of the service.
* `ResponseBuilder` : is used to build the response of the API in One & Same Structure.

**MODULES**

Every module has its own folder and the folder contains the following files:
* `Model` : is used to define the database schema.
* `Request` : is used to validate the request data.
* `Service` : is used to make the business logic of the data.
* `Repository` : is used to make the data layer.
* `DTO` : is used to transfer the data between the layers.
* `Mapper` : is used to map the data between the layers.
* `context` : is used to store static data and make the code more maintainable.

___
## Usage

#### API Documentation
* [Postman Documentation](https://documenter.getpostman.com/view/4199586/2s8YsqUEWL)

#### API Endpoints

#### GET `/api/v1/users`
* **Headers**
   * `Accept` : `application/json`
   * `Content-Type` : `application/json`
   * `Connection` : `keep-alive`


   * **Query Parameters**
       * `statusCode` : (`string`).
       * `currency` : (`string`).
       * `provider` : (`string`).
       * `balanceMin` : (`integer` | min:`0`) , min amount of transaction.
       * `balanceMax` : (`integer` | min:`0` | >`balanceMin`) , max amount of transaction.
       * `dateFrom` : (`date` | timezone:`UTC`) , min date of transaction.
       * `dateTo` : (`date` | timezone:`UTC`| >`dateFrom`) , max date of transaction.
       * `pageSize` : (`integer` | min:`1`) , number of items per page.
       * `pageNo` : (`integer` | min:`1`) , page number.
       * and can use all of them together 
         ```sh
         api/v1/users?provider=DataProviderX&balanceMin=0&currency=AED&statusCode=Authorised&balanceMax=10&pageSize=10&pageNo=1&dateFrom=2018-12-01&dateTo=2022-12-20
         ```
     
   * **Response**
      * `data` : `array`
      * `errors` : `array`
      * `message` : `string`
      * `status_code` : `integer`
      
