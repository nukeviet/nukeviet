<?php

declare(strict_types=1);

namespace Webauthn;

abstract class PublicKeyCredentialEntity
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $icon
    ) {
    }
}
