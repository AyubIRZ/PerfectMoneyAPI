<?php


namespace AyubIRZ\PerfectMoneyAPI;

use Exception;

class PerfectMoneyAPI
{
    /*
     * @var integer AccountID: the username of your PM account.
    */
    protected $AccountID;


    /*
     * @var string PassPhrase: the password of your PM account.
    */
    protected $PassPhrase;


    /**
     * Constructor
     *
     */
    public function __construct($AccountID, $PassPhrase)
    {
        $this->AccountID = $AccountID;

        $this->PassPhrase = $PassPhrase;
    }


    /**
     * Fetch the public name of another existing PerfectMoney account
     *
     */
    public function getAccountName($account)
    {
        // trying to open URL to process PerfectMoney getAccountName request
        $data = file_get_contents("https://perfectmoney.is/acct/acc_name.asp?AccountID={$this->AccountID}&PassPhrase={$this->PassPhrase}&Account={$account}");

        if($data == 'ERROR: Can not login with passed AccountID and PassPhrase'){

            throw new Exception('Invalid PerfectMoney Username or Password.', 500);

        }elseif($data == 'ERROR: Invalid Account'){

            throw new Exception('Invalid PerfectMoney Account specified.', 500);

        }

        return $data;
    }


    /**
     * get the balance for the wallet or a specific account inside a wallet
     *
     */
    public function getBalance($account = null)
    {
        // trying to open URL to process PerfectMoney Balance request
        $data = file_get_contents("https://perfectmoney.is/acct/balance.asp?AccountID={$this->AccountID}&PassPhrase={$this->PassPhrase}");

        // searching for hidden fields
        if (!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $data, $result, PREG_SET_ORDER)) {
            return false;
        }

        // putting data to array
        $array = [];

        foreach ($result as $item) {
            $array[$item[1]] = $item[2];
        }

        if ($account == null) {
            return $array;
        }

        return $array[$account] ?? false;
    }


    /**
     * Transfer funds(currency) to another existing PerfectMoney account
     *
     */
    public function transferFund($fromAccount, $toAccount, $amount, $paymentID = null, $memo = null)
    {
        $urlString = "https://perfectmoney.is/acct/confirm.asp?AccountID={$this->AccountID}&PassPhrase={$this->PassPhrase}&Payer_Account={$fromAccount}&Payee_Account={$toAccount}&Amount={$amount}&PAY_IN=1";

        $urlString .= ($paymentID != null) ? "&PAYMENT_ID={$paymentID}" : "";

        $urlString .= ($paymentID != null) ? "&Memo={$memo}" : "";

        // trying to open URL to process PerfectMoney Balance request
        $data = file_get_contents($urlString);

        // searching for hidden fields
        if (!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $data, $result, PREG_SET_ORDER)) {
            return false;
        }

        // putting data to array
        $array = [];

        foreach ($result as $item) {
            $array[$item[1]] = $item[2];
        }

        return $array;
    }


    /**
     * Create new E-Voucher with your PerfectMoney account
     *
     */
    public function createEV($payerAccount, $amount)
    {
        // trying to open URL to process PerfectMoney Balance request
        $data = file_get_contents("https://perfectmoney.is/acct/ev_create.asp?AccountID={$this->AccountID}&PassPhrase={$this->PassPhrase}&Payer_Account={$payerAccount}&Amount={$amount}");

        // searching for hidden fields
        if (!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $data, $result, PREG_SET_ORDER)) {
            return false;
        }

        // putting data to array
        $array = [];

        foreach ($result as $item) {
            $array[$item[1]] = $item[2];
        }

        return $array;
    }

    public function transferEV($toAccount, $EVnumber, $EVactivationCode)
    {
        // trying to open URL to process PerfectMoney Balance request
        $data = file_get_contents("https://perfectmoney.is/acct/ev_activate.asp?AccountID={$this->AccountID}&PassPhrase={$this->PassPhrase}&Payee_Account={$toAccount}&ev_number={$EVnumber}&ev_code={$EVactivationCode}");

        // searching for hidden fields
        if (!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $data, $result, PREG_SET_ORDER)) {
            return false;
        }

        // putting data to array
        $array = [];

        foreach ($result as $item) {
            $array[$item[1]] = $item[2];
        }

        return $array;
    }
}