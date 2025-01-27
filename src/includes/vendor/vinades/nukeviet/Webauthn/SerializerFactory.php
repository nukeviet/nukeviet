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

use Cose\Algorithm\Manager;
use Cose\Algorithm\Signature\ECDSA;
use Cose\Algorithm\Signature\RSA;
use Symfony\Component\Clock\NativeClock;
use Webauthn\AttestationStatement\AndroidKeyAttestationStatementSupport;
use Webauthn\AttestationStatement\AppleAttestationStatementSupport;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\FidoU2FAttestationStatementSupport;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\AttestationStatement\PackedAttestationStatementSupport;
use Webauthn\AttestationStatement\TPMAttestationStatementSupport;
use Webauthn\Denormalizer\WebauthnSerializerFactory;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * NukeViet\Webauthn\SerializerFactory
 *
 * Tạo tình dịch các đối tượng Webauthn ra json
 *
 * @package NukeViet\Webauthn
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class SerializerFactory
{
    /**
     * @return SerializerInterface
     */
    public static function create(): SerializerInterface
    {
        $clock = new NativeClock();

        // Trong website hoặc xác thực 2 bước chỉ cần Attestation None là đủ
        $attestMgr = AttestationStatementSupportManager::create();
        $attestMgr->add(NoneAttestationStatementSupport::create());

        $attestMgr->add(FidoU2FAttestationStatementSupport::create());
        $attestMgr->add(AppleAttestationStatementSupport::create());

        $attestMgr->add(AndroidKeyAttestationStatementSupport::create());
        $attestMgr->add(TPMAttestationStatementSupport::create($clock));

        $coseAlgorithmManager = Manager::create();
        $coseAlgorithmManager->add(ECDSA\ES256K::create());
        $coseAlgorithmManager->add(ECDSA\ES256::create());
        $coseAlgorithmManager->add(RSA\RS256::create());

        $attestMgr->add(PackedAttestationStatementSupport::create($coseAlgorithmManager));

        $factory = new WebauthnSerializerFactory($attestMgr);
        return $factory->create();
    }
}
