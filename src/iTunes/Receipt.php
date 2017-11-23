<?php

namespace RaazPuspa\IAPValidator\iTunes;

use RaazPuspa\IAPValidator\Exceptions\IAPValidatorException as Exception;

class Receipt
{
    /**
     * Receipt is validated successfully and everything went true
     */
    const RESULT_OK = 0;

    /**
     * The App Store could not read the JSON object you provided
     */
    const COULD_NOT_READ = 21000;

    /**
     * The data in the receipt-data property was malformed or missing
     */
    const RECEIPT_DATA_MALFORMED_OR_MISSING = 21002;

    /**
     * The receipt could not be authenticated
     */
    const RECEIPT_NOT_AUTHENTICATED = 21003;

    /**
     * The shared secret you provided does not match the shared secret on file
     * for your account
     */
    const UNMATCHED_SHARE_SECRET = 21004;

    /**
     * The receipt server is not currently available
     */
    const SERVER_NOT_AVAILABLE = 21005;

    /**
     * This receipt is valid but the subscription has expired. When this status
     * code is returned to your server, the receipt data is also decoded and
     * returned as part of the response
     * Only returned for iOS 6 style transaction receipts for auto-renewable
     * subscriptions.
     */
    const SUBSCRIPTION_HAS_EXPIRED = 21006;

    /**
     * This receipt is from the test environment, but it was sent to the
     * production environment for verification. Send it to the test
     * environment instead
     */
    const SANDBOX_RECEIPT_SENT_TO_PRODUCTION = 21007;

    /**
     * This receipt is from the production environment, but it was sent to the
     * test environment for verification. Send it to the production
     * environment instead
     */
    const PRODUCTION_RECEIPT_SENT_TO_SANDBOX = 21008;

    /**
     * This receipt could not be authorized. Treat this the same as if a
     * purchase was never made
     */
    const RECEIPT_NOT_AUTHORIZED = 21010;

    /**
     * Internal data access error, minimum value
     */
    const INTERNAL_DATA_ACCESS_ERROR_MIN = 21100;

    /**
     * Internal data access error, maximum value
     */
    const INTERNAL_DATA_ACCESS_ERROR_MAX = 21199;

    /**
     * response status code
     *
     * @var int
     */
    protected $_code;

    /**
     * current app environment
     *
     * @var string
     */
    protected $_environment;

    /**
     * in-app purchase receipt information
     *
     * @var array
     */
    protected $_receipt;

    /**
     * in-app purchase information
     *
     * @var array
     */
    protected $_in_app;

    /**
     * information of latest in-app purchase receipt
     *
     * @var array
     */
    protected $_latest_receipt_info;

    /**
     * latest in-app purchase receipt
     *
     * @var string
     */
    protected $_latest_receipt;

    /**
     * pending renewal information for the receipt
     *
     * @var array
     */
    protected $_pending_renewal_info;

    public function __construct($response = NULL)
    {
        if ($response != NULL) {
            $this->parseResponse($response);
        }
    }

    /**
     * get response status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->_code;
    }

    /**
     * get current app environment
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->_environment;
    }

    /**
     * get receipt information
     *
     * @return array
     */
    public function getReceipt()
    {
        return $this->_receipt;
    }

    /**
     * get in-app information
     *
     * @return array
     */
    public function getInApp()
    {
        return $this->_in_app;
    }

    /**
     * get latest receipt information
     *
     * @return array
     */
    public function getLatestReceiptInfo()
    {
        return $this->_latest_receipt_info;
    }

    /**
     * get latest receipt string (base64 encoded)
     *
     * @return string
     */
    public function getLatestReceipt()
    {
        return $this->_latest_receipt;
    }

    /**
     * get pending renewal information
     *
     * @return array
     */
    public function getPendingRenewalInfo()
    {
        return $this->_pending_renewal_info;
    }

    /**
     * parse the respones receipt to extract embeded attributes
     *
     * @param array $respone
     */
    function parseResponse($response)
    {
        if (!is_array($response)) {
            throw new Exception('Response must be a scalar value');
        }

        if (array_key_exists('receipt', $response) &&
            is_array($response['receipt']) &&
            array_key_exists('in_app', $response['receipt']) &&
            is_array($response['receipt']['in_app'])
        ) {
            $this->_code        = $response['status'];
            $this->_environment = $response['environment'];
            $this->_receipt     = $response['receipt'];

            if (array_key_exists('in_app', $this->_receipt)) {
                $this->_in_app = $response['receipt']['in_app'];
            }

            if (array_key_exists('latest_receipt_info', $response)) {
                $this->_latest_receipt_info = $response['latest_receipt_info'];
            }

            if (array_key_exists('latest_receipt', $response)) {
                $this->_latest_receipt = $response['latest_receipt'];
            }

            if (array_key_exists('pending_renewal_info', $response)) {
                $this->_pending_renewal_info =
                    $response['pending_renewal_info'];
            }
        } else if (array_key_exists('receipt', $response)) {
            $this->_code        = $response['status'];
            $this->_environment = $response['environment'];
            $this->_receipt     = $response['receipt'];

            if (array_key_exists('in_app', $this->_receipt)) {
                $this->_in_app = $response['receipt']['in_app'];
            }
        } else if (array_key_exists('status', $response)) {
            $this->_code = $response['status'];
        } else {
            $this->_code = self::RECEIPT_DATA_MALFORMED_OR_MISSING;
        }
    }
}
