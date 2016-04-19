## Synopsis

This is the official API client for the Afosto API. This client, written in PHP should make your work when it comes to interacting with the API a lot easier.


## Code Example

Connect to the API using your OAUTH2 client and secret, use a cache driver to store the credentials and start interacting.

```php
$storage = new SessionStorage();
App::run($storage, CLIENT_ID, CLIENT_SECRET);
```

For example store new customer data and use the newly assigned id right away for a new sale.

```php
$customer = Customer::model();
$customer->email = 'support@afosto.com';
$customer->name = 'Peter Bakker';
$customer->is_female = false;
$customer->save();
```

```php
$sale = new Sale::model();
$sale->customer_id = $customer->id;
```

Also it is possible to paginate and then traverse through objects that are connected based on relations.

```php
foreach (Product::model()->paginate(50) as $page => $products) {
    foreach ($products as $product) {
        foreach ($product->collections as $collection) {
            echo $collection->name;
        }
    }
}
```


## Installation

You should use [Composer](https://getcomposer.org/) to install this client, using the following command:

```sh
composer require afosto/api-client
```

After this create a new file config.php in the base dir of the project and set the values accordingly as specified in config.example.php.


## API Reference

Visit [the docs](https://docs.afosto.com) for the full list of documentation and use the inline documentation.


## Tests

Todo.


## Contributors

You are welcome to contribute to this library, any questions can asked directly at the lead API developer [Sjoerd](mailto:sjoerd@afosto.com). Moreso you are encouraged to do pull-requests if you find your code complementairy to the client.


## License

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at 

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.