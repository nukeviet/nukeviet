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

use Webauthn\MetadataService\Statement\AuthenticatorStatus;
use Webauthn\MetadataService\Statement\StatusReport;
use Webauthn\MetadataService\StatusReportRepository as WebAuthnStatusReportRepository;

/**
 * NukeViet\Webauthn\StatusReportRepository
 *
 * Là một repository chứa các Status Reports (báo cáo trạng thái) về các authenticator.
 * Status Reports cung cấp thông tin về:
 * - Trạng thái hiện tại của authenticator (ví dụ: đang hoạt động, bị thu hồi, bị hỏng).
 * - Các cảnh báo hoặc lỗi liên quan đến authenticator.
 * Mục đích: Giúp xác định xem một authenticator có đáng tin cậy hay không.
 *
 * @package NukeViet\Webauthn
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class StatusReportRepository implements WebAuthnStatusReportRepository
{
    /**
     * @param string $aaguid
     * @return StatusReport[]
     */
    public function findStatusReportsByAAGUID(string $aaguid): array
    {
        // Logic để tìm Status Reports dựa trên AAGUID
        // Ví dụ: truy vấn từ cơ sở dữ liệu hoặc tệp JSON
        return $this->getStatusReportsFromSource($aaguid);
    }

    /**
     * Lấy Status Reports từ nguồn dữ liệu
     * Tạm thời xem ok
     *
     * @param string $aaguid
     * @return StatusReport[]
     */
    private function getStatusReportsFromSource(string $aaguid): array
    {
        return [
            StatusReport::create(
                status: AuthenticatorStatus::FIDO_CERTIFIED, // fake status
                effectiveDate: null,
                certificate: null,
                url: null,
                certificationDescriptor: null,
                certificateNumber: null,
                certificationPolicyVersion: null,
                certificationRequirementsVersion: null
            )
        ];
    }
}
