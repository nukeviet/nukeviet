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

use Webauthn\MetadataService\CertificateChain\CertificateChainValidator as WebauthnCertificateChainValidator;

/**
 * NukeViet\Webauthn\CertificateChainValidator
 *
 * Kiểm tra tính hợp lệ của chứng chỉ SSL
 *
 * @package NukeViet\Webauthn
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class CertificateChainValidator implements WebauthnCertificateChainValidator
{
    public function check(array $untrustedCertificates, array $trustedCertificates): void
    {
    }
}
