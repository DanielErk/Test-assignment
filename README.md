# Address Distance Calculator
**A PHP application to calculate the distance between different addresses and save the results to a CSV file.**

## Prerequisites
Make sure you have the following software installed:

* PHP (Version 7.4 or higher recommended)
* Composer (for managing PHP dependencies)
* Git (for version control)

## Installation
Follow these steps to set up the project:

### Install dependencies using Composer:

Run the following command to install the necessary PHP dependencies:

```composer install```

### Set up the environment file:

Copy the .env.example to .env

```cp .env.example .env```


## Run the script:

To execute the application, run the following command:

```php public/index.php```

This will calculate the distance between the RMT headquarters in Rotterdam and the provided addresses in the src/Config/Addresses.php file and output the results to the storage/addresses.csv file.