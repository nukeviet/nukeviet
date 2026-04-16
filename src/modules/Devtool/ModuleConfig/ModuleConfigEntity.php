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
    private array $groups = [];

    /**
     * ModuleConfigEntity constructor.
     * @param string $module
     * @param array $groups
     */
    public function __construct(string $module = '', array $groups = [])
    {
        $this->module = $module;
        $this->groups = $groups;
    }

    /**
     * @return string
     */
    public function getModule(): string
    {
        return $this->module;
    }

    /**
     * @param string $module
     */
    public function setModule(string $module): void
    {
        $this->module = $module;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param array $groups
     */
    public function setGroups(array $groups): void
    {
        $this->groups = $groups;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'module' => $this->module,
            'groups' => $this->groups
        ];
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['module'] ?? '',
            $data['groups'] ?? []
        );
    }
}
