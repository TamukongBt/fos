# MTN Cameroon Mobile Money Package for Laravel & PHP

### Installation
```bash
composer require malico/momo
```
### Setup

#### Laravel (Only)

1. Publish the Configuration file with ``` php artisan vendor:publish --tag=momo-configuration ```
2. Update  ``` app/momo.php ``` with the approriate configurations.

	Configurations
	* email - Service used to create account on 	[https://developer.mtn.cm](https://developer.mtn.cm)
		> MOMO_EMAIL=email_address (.env)
	* default_price - Default price (amount)
		> MOMO_DEFAULT_PRICE=100 (.env), Default : 100
	* foreign_key - Column name in your migration that holds the Transactions

    	- Make sure you update your migrations to match the foreign_key provided in configuration file incase you want to use MomoTransaction trait

        ```php
          Schema::table('sales', function (Blueprint $table) {
                $table->integer('momo_transaction_id')->nullable();
            });
        ```

3. Run ```php artisan migrate ``` to run DB Migrations

### Usage

##### PHP

```php
<?php

require 'composer/autoload.php';

use Malico\Momo\Momo;

$momo = new Momo('67777777', 100);
/**
 * Availabe Methods
 * 
 * $momo = (new Momo())
 *         ->amount(100)
 *         ->email('yourEmail@domain.com')
 *         ->tel(67777777);
 */

$momo->email('yourEmail@domain.com');

$transaction = $momo->pay();

/**
 * Transaction Properties
 *  - amount  // Amount [Int]
    - tel // User's tel [String]
    - status // Transaction Status [Bool]
    - comment // Comment [String]
    - reference // Reference ID [String]
    - receiver_tel // [String]
    - operation_type
    - transaction_id // Transaction ID [String]
    - desc // Trnsaction Description [Text]
 *  - 
 */
if($transaction->status){
    // Payment was successful
    // do something, save to Database or anyting
} else {
    // Failed Transaction
    // do something Display error message 
    // echo $transaction->desc;
}
```
* With Vanilla PHP, Alwasy include the `email()` method to configure Payment Email

##### Laravel

Add MomoTransaction trait to your model to relate model to Transaction Models and other payment methods

```php
<?php
// app/Sale.php
// Eloquent Model
namespace App;

use Malico\Momo\Support\MomoTransaction;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
	// use Momo\Support\MomoTransaction Trait
    use MomoTransaction;

    ...
}
```

```php

<?php
// SalesController.php
namespace App\Http\Controllers;

use App\Sale;
use Malico\Momo\Momo;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
    	...
        $sale = new Sale();

        $sale->pay('672727272', 100);
        /**
         * Also 
         *    $sale->momo(676956703, 100)
                ->tel(678513819)
                ->amount(300)
                ->pay();
         */
        
        if ($sale->momo_transaction->status) {
            // Do something
            // ... code
        }  else {
           // Payment was unsucessful;
           // return view('incomeplte_payment', ['message' => $sale->momo_transaction->desc])
        }
    }
}
```

### Contribute
All contributions are welcomed, but hey before working on a feature, please kindly suggest it as a new issue. And remember Clean code Rocks.

##### License
MIT

