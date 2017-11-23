<?php

namespace RaazPuspa\IAPValidator\iTunes;

use \GuzzleHttp\Client as HttpClient;
use RaazPuspa\IAPValidator\Exceptions\IAPValidatorException as Exception;
use RaazPuspa\IAPValidator\Contracts\IAPValidator as IAPValidatorContract;

/**
 * handles receipt validation of iTunes purchases
 *
 * @author Pusparaj Bhattarai <mail@pusparaaz.com.np>
 */
class IAPValidator implements IAPValidatorContract
{
    const PRODUCTION_ENDPOINT = "https://buy.itunes.apple.com/verifyReceipt";
    const SANDBOX_ENDPOINT = "https://sandbox.itunes.apple.com/verifyReceipt";

    /**
     * end point url
     *
     * @var string
     */
    protected $_endPoint = '';

    /**
     * iTunes receipt data (base64 encoded version)
     *
     * @var string
     */
    protected $_receiptData = '';

    /**
     * The shared secret is a unique code to receive you in-app purchase
     * receipt. Without a shared secret you will not be able to test or offer
     * your automatically renewable in-app purchase subscriptions.
     *
     * @var string
     */
    protected $_sharedSecretKey = '';

    /**
     * server response model
     *
     * @var Receipt
     */
    protected $_receipt = NULL;

    /**
     * Guzzle Http Client
     *
     * @var HttpClient
     */
    protected $_client = NULL;

    /**
     * iTunes in-app purchase validator constructor
     *
     * @param HttpClient $client
     * @return void
     */
    public function __construct(HttpClient $client)
    {
        $this->_client          = $client;
        $this->_sharedSecretKey = env('IAP_ITUNES_SECRET', NULL);

        if (!$this->_sharedSecretKey) {
            throw new Exception(
                'Unable to locate iTunes shared secret key '
                . '(IAP_ITUNES_SECRET) in .env file or is empty/null'
            );
        }
    }

    /**
     * encode request receipt data and shared secret key in json
     *
     * @return string
     */
    function encodeRequest()
    {
        if (!is_null($receiptData = $this->getReceiptData())) {
            $request = ['receipt-data' => $receiptData];
        } else {
            throw new Exception('Receipt data is not provided');
        }


        if (!is_null($sharedSecretKey = $this->getSharedSecretKey())) {
            $request['password'] = $sharedSecretKey;
        }

        return json_encode($request);
    }

    /**
     * get iTunes provided shared secret key
     *
     * @return string
     */
    function getSharedSecretKey()
    {
        return $this->_sharedSecretKey;
    }

    /**
     * get Guzzle Http Client object instance
     *
     * @return HttpClient
     */
    function getHttpClient()
    {
        return $this->_client;
    }

    /**
     * get the base64 encoded receipt data
     *
     * @return string
     */
    function getReceiptData()
    {
        return $this->_receiptData;
    }

    /**
     * set receipt string
     *
     * @param string $receiptData
     */
    function setReceiptData($receiptData = NULL)
    {
        if (is_null($receiptData)) {
            throw new Exception('receipt data should not be empty or null');
        }

        $this->_receiptData = $receiptData;
    }

    /**
     * validator end-point getter
     *
     * @return string
     */
    public function getEndPoint()
    {
        return $this->_endPoint;
    }

    /**
     * validator end-point setter
     *
     * @param string
     * @throws Exception
     * @return IAPValidator
     */
    public function setEndPoint($endPoint = self::PRODUCTION_ENDPOINT)
    {
        if (!$endPoint) {
            throw new Exception('end point is not provided');
        } else if ($endPoint !== self::PRODUCTION_ENDPOINT &&
            $endPoint !== self::SANDBOX_ENDPOINT) {
            throw new Exception('invalid end-point provided');
        }

        $this->_endPoint = $endPoint;

        return $this;
    }

    /**
     * validate the receipt data
     *
     * @param string $receiptData
     * @param string $endPoint
     * @throws Exception
     * @return Receipt
     */
    public function validateReceipt($receiptData, $endPoint = NULL)
    {
        if (!is_null($receiptData)) {
            $this->setReceiptData($receiptData);
        } else {
            throw new Exception('Receipt data is not provided');
        }

        if (!is_null($endPoint)) {
            $this->setEndPoint($endPoint);
        } else {
            $endPoint = $this->getEndPoint() ?: self::PRODUCTION_ENDPOINT;
        }

        $httpResponse = $this->getHttpClient()->request(
            'POST', $endPoint, ['body' => $this->encodeRequest()]
        );

        if ($httpResponse->getStatusCode() !== 200) {
            throw new Exception('Unable to get response from iTunes server');
        }

        $this->_receipt =
            new Receipt(json_decode($httpResponse->getBody(), true));

        $trySandbox = $this->_receipt->getStatusCode() ===
            Receipt::SANDBOX_RECEIPT_SENT_TO_PRODUCTION;

        if ($this->getEndPoint() === self::PRODUCTION_ENDPOINT
            && $trySandbox) {
            return $this->validateReceipt(
                $receiptData, self::SANDBOX_ENDPOINT
            );
        }

        $tryProduction = $this->_receipt->getStatusCode() ===
            Receipt::PRODUCTION_RECEIPT_SENT_TO_SANDBOX;

        if ($this->getEndPoint() === self::SANDBOX_ENDPOINT
            && $tryProduction) {
            return $this->validateReceipt(
                $receiptData, self::PRODUCTION_ENDPOINT
            );
        }

        return $this->_receipt;
    }
}
