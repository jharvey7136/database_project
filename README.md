Sample Database Application
===========================

## John Harvey

### Install Notes
#### Ubuntu - [Reference](https://howtoubuntu.org/how-to-install-lamp-on-ubuntu)
1. Install LAMP stack if not already installed
  * Update system `sudo apt update && sudo apt upgrade`
  * Apache `sudo apt-get install apache2`
  * MySQL  `sudo apt-get install mysql-server mysql-common`
  * PHP    `sudo apt-get install php5 libapache2-mod-php5`
  * Restart Server `sudo /etc/init.d/apache2 restart`
  * Check Apache Open a web browser and navigate to http://localhost/. You should see a message saying It works!
  * Check PHP `php -r 'echo "\n\nYour PHP installation is working fine.\n\n\n";'`
2. Clone repo with git `git clone`
3. Navigate to the app's root directory through the terminal
4. Run SQL script to create the database and schema `mysql -u root < create_mail.sql`
5. Run application `php application.php`

### Database Schema
```
EMPLOYEES (ENO, ENAME, ZIP, HIRE_DATE);
PARTS (PNO, PNAME, QUANTITY_ON_HAND, UNIT_PRICE, LEVEL);
CUSTOMERS (CNO, CNAME, STREET, ZIP, PHONE);
ORDERS (ONO, CNO, ENO, RECEIVED, SHIPPED);
ORDER_LINE (ONO, PNO, QTY);
ZIPCODES (ZIP, CITY);
```

### Application Tasks
1. add a customer
2. add an order
3. remove an order
4. ship an order
5. print pending order list with customer information
6. restock parts
7. exit

* User will be promoted to enter the necessary info, one field at a name.
* When enter a new customer:
* CNO should be automatically generated. (e.g. the biggest existing number + 1).
* ZIP should exist in ZIPCODES table. If not, add the entry into ZIPCODES by asking for CITY.
* When add an order:
* Add to both ORDERS and ORDER_LINE. ONO and RECEIVED should be automatically filled.
* Pay attention to the foreign key constraints on CNO, ENO and PNO.
* Update the part's QUANTITY_ON_HAND.
* The order should be rejected if it makes the updated `QUANTITY_ON_HAND  < 0`
* When remove an order:
   Delete the entry in both the ORDER and the ORDER_LINE tables.
   NEVER forget to restock parts (update the QUANTITY_ON_HAND attribute)
   When printing pending order list:
   Print only pending orders (i.e. orders have not been shipped).
   Print them in the order of received time.
   Appropriate error-checking and error-handling is expected.
   A user might enter a new record whose key already exists in the table. Handle this appropriately.

### Known Issues
MySQL functions were deprecated in PHP 5.5.0, and were removed in PHP 7.0.0. This program was written in php5.
 




   
