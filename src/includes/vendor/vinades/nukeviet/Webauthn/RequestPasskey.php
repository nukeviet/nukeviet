<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Webauthn;

use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Webauthn\PublicKeyCredentialRequestOptions;

/**
 * NukeViet\Webauthn\RequestPasskey
 *
 * Tạo json yêu cầu đăng nhập passkey
 *
 * @package NukeViet\Webauthn
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class RequestPasskey
{
    public static function create()
    {
        $serializer = SerializerFactory::create();

        // preferred, required timeout từ 300 đến 600, discouraged timeout từ 30 đến 180
        $requestOptions = PublicKeyCredentialRequestOptions::create(
            random_bytes(32),
            userVerification: PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_REQUIRED,
            rpId: NV_SERVER_NAME,
            timeout: 300
        );
        return $serializer->serialize(
            $requestOptions,
            'json',
            [
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                JsonEncode::OPTIONS => JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT,
            ]
        );
    }
}
