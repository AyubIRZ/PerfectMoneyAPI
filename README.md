# PerfectMoneyAPI

[![Latest Stable Version](https://poser.pugx.org/ayubirz/perfect-money-api/v/stable)](https://packagist.org/packages/ayubirz/perfect-money-api)  [![Total Downloads](https://poser.pugx.org/ayubirz/perfect-money-api/downloads)](https://packagist.org/packages/ayubirz/perfect-money-api)  [![Latest Unstable Version](https://poser.pugx.org/ayubirz/perfect-money-api/v/unstable)](https://packagist.org/packages/ayubirz/perfect-money-api)  [![License](https://poser.pugx.org/ayubirz/perfect-money-api/license)](https://packagist.org/packages/ayubirz/perfect-money-api)

An easy to use PHP package to integrate PerfectMoney online payment API into your application.
You can perform basic functions provided by [PerfectMoney](https://perfectmoney.is) payment gateway.

Just install the package and it is ready to use! There is no additional work requiered!

Requirements
============

* PHP >= 7.0

Installation
============

If you use composer as your dependency manager, you can download and install the package super easy by just running below command inside your project root directory:

    $ composer require ayubirz/perfect-money-api:dev-master

Currently there is only dev-master available. Soon there will be stable releases to install and use.

Usage
=====

After installing the package, you should first "use" the package's namespace inside your code where you want to use **PerfectMoneyAPI** methods. For example in your Controller you should type below code at the beggining of your file, right after the **namespace your/name/space;** line:

    use AyubIRZ\PerfectMoneyAPI\PerfectMoneyAPI;
    
Then, you should initiate an instance of **PerfectMoneyAPI** class, wherever you needed it, as below:

    $PMAccountID = '1234567';      // This should be replaced with your real PerfectMoney Member ID(username that you login with).
    
    $PMPassPhrase = 'mydummypass'; // This should be replaced with your real PerfectMoney PassPhrase(password that you login with).
    
    $PM = new PerfectMoneyAPI($PMAccountID, $PMPassPhrase);

After creating an instance of **PerfectMoneyAPI** class, you would be able to use available methods inside it.

**Getting an existing PerfectMoney account's public name**

    $accountID = 'U123456'; // A valid PM currency account ID that you want to get it's name.
    
    try{
      $PMname = $PM->getAccountName($accountID); // The account name(if it's valid) will return. Otherwise you have an error.
    } catch (Exception $exception) {
      return $exception->getMessage();
    }
    
    return $PMname;
 
If any error happens, exceptions will be thrown. Check the code for more information about exceptios.


**Getting the complete balance(current amount of currencies) of your account**
   
    $PMbalance = $PM->getBalance(); // array of all your currency wallets(as keys) and all of their balances(as values) will return.
    
    return json_encode($PMbalance);


**Getting a specific wallet's balance(current amount of currency) of your account**

If you want to have only one specific wallet's balance in return, you should specify one of your wallet IDs as the only parameter to below method. For example, I want to get my USD wallet's balance:
   
    $PMAccountID = 'U123456'; // Replace this with one of your wallet IDs.
    
    $PMbalance = $PM->getBalance($PMAccountID); // A float number indicating your input wallet's balance will return(For example 250.05 USD in my case).
    
    return json_encode($PMbalance);
    
    
**Transfer funds(currency, money) to another existing PerfectMoney account**

If you want to transfer amounts of money(currency) to any other PerfectMoney account, you can use below method with the specified parameters:
   
    $fromAccount = 'U123456'; // Replace this with one of your own wallet IDs that you want to transfer currency from.
    
    $toAccount = 'U654321'; // Replace this with the destination wallet ID that you want to transfer currency to.
    
    $amount = 250; // Replace this with the amount of currency unit(in this case 250 USD) that you want to transfer.
    
    $paymentID = microtime(); // Replace this with a unique payment ID that you've generated for this transaction. This can be the ID for the database stored record of this payment for example(Up to 50 characters). ***THIS PARAMETER IS OPTIONAL***
    
    $memo = 250; // Replace this with a description text that will be shown for this transaction(Up to 100 characters). ***THIS PARAMETER IS OPTIONAL***
    
    $PMtransfer = $pm->transferFund($fromAccount, $toAccount, $amount, $paymentID, $memo); // An array of previously provided data will return for a valid and successful transaction. If any error happen, an array with one item with the key "ERROR" will return.
    
    return json_encode($PMtransfer);
    

**Creating new PerfectMoney E-Voucher**

***E-Voucher*** are like gift cards. An ***E-Voucher*** may has some amount of currency credit in it and it can be used to fund an account or etc. To create a new ***E-Voucher*** from one of your wallet's credit, you should use below method as followed:
   
    $payerAccount = 'U123456'; // Replace this with one of your own wallet IDs that you want to create the E-Voucher from it.
        
    $amount = 250; // Replace this with the amount of currency unit(in this case 250 USD) that you want to fund the created E-Voucher. E-Voucher amount unit must be greater than 1.
    
    $PMeVoucher = $pm->createEV($payerAccount, $amount); // An array of E-Voucher data(Payer_Account, PAYMENT_AMOUNT, PAYMENT_BATCH_NUM, VOUCHER_NUM, VOUCHER_CODE, VOUCHER_AMOUNT) will return for a valid and successful transaction. If any error happen, an array with one item with the key "ERROR" will return.
    
    return json_encode($PMeVoucher);
    
The returned array is as below:

* Payer_Account: The account which you used to create the E-Voucher.

* PAYMENT_AMOUNT: Numerical amount of E-Voucher as entered. This is the total amount you payed icluding fee.

* PAYMENT_BATCH_NUM: PerfectMoney batch number generated for this transaction. You may query/search account history by this number.

* VOUCHER_NUM: Unique number of purchased E-Voucher contaning 10 digits.

* VOUCHER_CODE: Activation code of purchased E-Voucher contaning 16 digits.

* VOUCHER_AMOUNT: Nominal amount of E-Voucher. This is the same value as the amount input field.

Support
=======

If you have found a bug or you have a usage issue, please use the [GitHub issue tracker](https://github.com/AyubIRZ/PerfectMoneyAPI/issues).

License
=======

This package is published under the MIT License (MIT). Please see [License File](https://github.com/AyubIRZ/PerfectMoneyAPI/blob/master/LICENSE) for more information.
