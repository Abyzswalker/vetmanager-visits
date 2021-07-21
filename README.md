# How to use
## Installation
```
composer require abyzs/vetmanager-visits
```
## Examples
```php
use Abyzs\VetmanagerVisits\VisitCounter;
use Abyzs\VetmanagerVisits\AuthApi;

$domain = new AuthApi('myclinic', 'api-key');
$visit = new VisitCounter();

/*
  Where 'myclinic' is first part from your clinic url. myclinic.vetmanager.ru
*/

echo $visit->dayCount($domain->getInvoices()); //Visits in one day
echo $visit->weekCount($domain->getInvoices()); //Visits Visits per week
```
