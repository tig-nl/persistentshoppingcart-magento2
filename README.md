**After careful consideration, we have decided that we will not actively maintain the persistent shopping cart extension anymore. We do however invite all developers to fork and improve the module.**

# Persistent Shopping Cart for Guests

## A Magento 2 module developed by TIG

Extends the core Magento 2 Persistent Shopping Cart with Cookies/Sessions for Guests by saving the session's Shopping Cart data to a cookie within the guest's browser. 

Whenever a guest (a user without a registered account) returns to your webshop, the shopping cart is updated with the data saved in the cookie. If a guest creates an account, the saved data will be saved to Magento 2's core Persistent Shopping Cart session.

For more information, go to [TIG.nl](https://tig.nl/persistent-shopping-cart-for-guests/).

### Installation

The easiest way to install this Magento 2 extension is by using composer. If you don't know how to use Composer or can't install Composer, you can either install it by using Git or manually.

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

The module can be enabled from within the Magento 2 backend: *Stores > Configuration > TIG > Persistent Shopping Cart for Guests > Enable Persistence for Guests*.

This extension *extends* Magento 2's core Persistant Shopping Cart functionality with features for guests. Make sure *Stores > Configuration > Customers > Persistent Shopping Cart > Enable Persistence* is set to **Yes** and on the same page *Persist Shopping Cart* is set to **Yes**.

### FAQ

##### Is this extension GDPR proof?

In short; **Yes!** For two reasons:
 1. The cookie created by this module does not contain any information which can identify an individual. 
 2. The cookie will be only created once Magento 2's built-in cookie notice has been accepted.

##### Is a guest's Wishlist and Add to Compare also saved?

No, not yet. This might be implemented in a future release though. 
