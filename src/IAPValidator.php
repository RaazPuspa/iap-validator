<?php
namespace PuspaRaaz\IAPValidator;

use PuspaRaaz\IAPValidator\Contracts\IAPValidator as IAPValidatorContract;

/**
 * provides detail package information
 *
 * @author Pusparaj Bhattarai <mail@pusparaaz.com.np>
 */
class IAPValidator
{
    const NAME          =   "pusparaaz/iap-validator";
    const URL           =   "https://pusparaaz.com.np/iap-validator";
    const GITHUB        =   "https://github.com/raazpuspa/iap-validator";
    const DESCRIPTION   =   "Composer package to validate in-app purchase "
                            ."receipts made to iTunes or Google through "
                            ." mobile applications.";
    const DEPENDENCIES  =   "PHP >=5.6";
    const USAGE         =   "composer require pusparaaz/iap-validator";
    const SUPPORT       =   "iap-validator@pusparaaz.com.np";
    const AUTHOR        =   [
        "name"      =>  "Pusparaj Bhattarai",
        "role"      =>  "Developer",
        "email"     =>  "mail@pusparaaz.com.np",
        "homepage"  =>  "https://pusparaaz.com.np",
        "location"  =>  "Balubadi - 7, KachanKawal, Jhapa",
        "country"   =>  "Nepal",
    ];

    /**
     * provide package information
     *
     * @return array
     */
    public function index()
    {
        return [
            "name"          => self::NAME,
            "description"   => self::DESCRIPTION,
            "url"           => self::URL,
            "github"        => self::GITHUB,
            "usage"         => self::USAGE,
            "dependencies"  => self::DEPENDENCIES,
            "author"        => self::AUTHOR,
            "support"       => self::SUPPORT,
        ];
    }
}
