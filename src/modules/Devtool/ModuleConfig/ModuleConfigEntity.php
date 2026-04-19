<?php
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

declare(strict_types=1);

namespace NukeViet\Module\Devtool\ModuleConfig;

/**
 * Class ModuleConfigEntity
 * @package NukeViet\Module\Devtool\ModuleConfig
 */
class ModuleConfigEntity
{
    private string $module;
    private string $op;
    private array $groups = [];

    /**
     * ModuleConfigEntity constructor.
     * @param string $module
     * @param string $op   Tên op (tương ứng tên file: admin/{op}.php, {op}.tpl)
     * @param array $groups
     */
    public function __construct(string $module = '', string $op = 'config', array $groups = [])
    {
        $this->module = $module;
        $this->op     = $op ?: 'config';
        $this->groups = $groups;
    }

    public function getModule(): string { return $this->module; }
    public function setModule(string $module): void { $this->module = $module; }

    public function getOp(): string { return $this->op; }
    public function setOp(string $op): void { $this->op = $op ?: 'config'; }

    public function getGroups(): array { return $this->groups; }
    public function setGroups(array $groups): void { $this->groups = $groups; }

    public function toArray(): array
    {
        return [
            'module' => $this->module,
            'op'     => $this->op,
            'groups' => $this->groups,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['module'] ?? '',
            $data['op']     ?? 'config',
            $data['groups'] ?? []
        );
    }
}
