<?php

namespace RaazPuspa\IAPValidator\Contracts;

/**
 * interface for validator classes
 *
 * @author Pusparaj Bhattarai <mail@pusparaaz.com.np>
 */

interface IAPValidator
{
    /**
     * encode request receipt data and shared secret key in json
     *
     * @return string
     */
    function encodeRequest();

    /**
     * get iTunes provided shared secret key
     *
     * @return string
     */
    function getSharedSecretKey();

    /**
     * get Guzzle Http Client object instance
     *
     * @return HttpClient
     */
    function getHttpClient();

    /**
     * get the base64 encoded receipt data
     *
     * @return string
     */
    function getReceiptData();

    /**
     * set receipt string
     *
     * @param string $receiptData
     */
    function setReceiptData($receiptData = NULL);

    /**
     * validator end-point getter
     *
     * @return string
     */
    public function getEndPoint();

    /**
     * validator end-point setter
     *
     * @param string
     * @throws Exception
     * @return IAPValidator
     */
    public function setEndPoint($endPoint = self::PRODUCTION_ENDPOINT);

    /**
     * validate the receipt data
     *
     * @param string $receiptData
     * @param string $endPoint
     * @throws Exception
     * @return array
     */
    public function validateReceipt($receiptData, $endPoint = NULL);
}
