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
 * Kiểm tra tính hợp lệ của Attestation Certificate Chain
 *
 * @package NukeViet\Webauthn
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class CertificateChainValidator implements WebauthnCertificateChainValidator
{
    /**
     * Chấp nhận tất cả, không ép dùng chứng chỉ do bên nào cấp.
     * Khi cần implement vào môi trường doanh nghiệm, muốn kiểm soát chỉ những key/cert
     * nào được phép thì sửa vào đây. Vui lòng không đánh giá bảo mật ở đây
     *
     * @param array $untrustedCertificates
     * @param array $trustedCertificates
     * @return void
     */
    public function check(array $untrustedCertificates, array $trustedCertificates): void
    {
    }
}
