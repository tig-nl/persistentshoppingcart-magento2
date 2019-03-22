# Persistent Shopping Cart for Guests
## A Magento 2 module developed by TIG

Extends the core Magento 2 Persistent Shopping Cart with Cookies/Sessions for Guests.

### Installation

The easiest way to install this Magento 2 extension is by using composer. If you don't know how to use Composer or don't have Composer installed, you can either install it by using Git or manually.

#### System requirements

Persistent Shopping Cart for Magento 2 has been tested on recent releases of Magento 2.1(.16), 2.2(.7) and 2.3(.0).

For maximum compatibility we recommend using PHP 7.0 or higher. Compatibility with PHP 5.6 and lower has not been tested and will not be supported.

#### Install using Composer

Run the following command in a terminal from within your Magento 2 root:

`composer require tig/persistent-shopping-cart-magento2`

#### Install using Git

First make sure you've created the following folder structure within your Magento 2 root: `app/code/TIG/PersistentShoppingCart`.

Execute the following command from within the `PersistentShoppingCart` directory:

`git clone https://github.com/tig-nl/persistentshoppingcart-magento2.git .`

#### Install manually

Download the latest release [here](https://github.com/tig-nl/persistentshoppingcart-magento2/archive/master.zip) and extract the archive within `[yourmagento2root]/app/code/TIG/PersistentShoppingCart`.

#### After Installation

To enable the module within Magento 2, you need to run `php -f bin/magento setup:upgrade`. 

If in production mode, don't forget to run `php -f bin/magento setup:di:compile` and `php -f bin/magento setup:static-content:deploy` after enabling the module.

### Configuration

The module can be enabled from within the Magento 2 backend

### FAQ
