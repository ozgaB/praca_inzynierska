<?php

namespace App\Utils;

class PaymentPayUConfig
{
    public function setPayUConfig()
    {
        /**
         * OpenPayU Examples
         *
         * @copyright  Copyright (c) 2011-2016 PayU
         * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
         * http://www.payu.com
         * http://developers.payu.com
         */

        //set Environment
        \OpenPayU_Configuration::setEnvironment('sandbox');

        //set POS ID and Second MD5 Key (from merchant admin panel)
        \OpenPayU_Configuration::setMerchantPosId('426548'); // POS ID (pos_id) / OAuth protocol - client_id
        \OpenPayU_Configuration::setSignatureKey('93f7f5913f7974725c96cf2fcbff4727'); // Second key (MD5)

        //set Oauth Client Id and Oauth Client Secret (from merchant admin panel)
        \OpenPayU_Configuration::setOauthClientId('426548'); // OAuth protocol - client_id / POS ID (pos_id)
        \OpenPayU_Configuration::setOauthClientSecret('1c46b123ede1153ab709b99aafc30060'); // Key (MD5) / OAuth protocol - client_secret
    }
}
