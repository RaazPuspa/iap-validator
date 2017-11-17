<?php
namespace PuspaRaaz\IAPValidator\iTunes;

use PuspaRaaz\IAPValidator\Contracts\IAPValidator as IAPValidatorContract;

/**
 * handles receipt validation of iTunes purchases
 *
 * @author Pusparaj Bhattarai <mail@pusparaaz.com.np>
 */
class IAPValidator implements IAPValidatorContract
{
    const ENDPOINT_LIVE = "https://buy.itunes.apple.com/verifyReceipt";
    const ENDPOINT_TEST = "https://sandbox.itunes.apple.com/verifyReceipt";

    /**
     * iTunes validator constructor
     *
     */
    public function __construct()
    {
    }

    /**
     * just a dummy method
     *
     * @return array
     */
    public function index()
    {
        return [
            "interface"     => "iOS",
            "test_url"      => self::ENDPOINT_TEST,
            "live_url"      => self::ENDPOINT_LIVE,
        ];
    }
}
