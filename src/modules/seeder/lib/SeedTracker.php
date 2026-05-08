<?php

/**
 * Seeder — SeedTracker
 *
 * Ghi log từng item đã seed vào bảng `nv5_seeder_log`. Mục tiêu:
 * - Idempotency: phát hiện item đã seed lần trước → skip hoặc update
 * - Reset an toàn: chỉ xóa item do seeder tạo (action='created'), không đụng item user nhập tay
 * - Status dashboard: tổng hợp số lượng theo step
 *
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 */

declare(strict_types=1);

namespace Seeder;

use PDO;

class SeedTracker
{
    private PDO $db;
    private string $logTable;
    private string $runId;

    public const ACTION_CREATED = 'created';
    public const ACTION_UPDATED = 'updated';
    public const ACTION_SKIPPED = 'skipped';
    public const ACTION_FAILED  = 'failed';

    public function __construct(PDO $db, string $tablePrefix)
    {
        $this->db = $db;
        $this->logTable = $tablePrefix . '_seeder_log';
        $this->runId = $this->generateUuid();
    }

    public function getRunId(): string
    {
        return $this->runId;
    }

    /**
     * Ghi 1 dòng log.
     */
    public function log(string $step, string $naturalKey, int $dbId, string $dbTable, string $action, ?string $message = null): void
    {
        $sql = "INSERT INTO {$this->logTable}
                (run_id, step, natural_key, db_id, db_table, action, message, run_time)
                VALUES (:run_id, :step, :natural_key, :db_id, :db_table, :action, :message, :run_time)";
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':run_id',      $this->runId);
        $sth->bindValue(':step',        $step);
        $sth->bindValue(':natural_key', $naturalKey);
        $sth->bindValue(':db_id',       $dbId, PDO::PARAM_INT);
        $sth->bindValue(':db_table',    $dbTable);
        $sth->bindValue(':action',      $action);
        $sth->bindValue(':message',     $message);
        $sth->bindValue(':run_time',    time(), PDO::PARAM_INT);
        $sth->execute();
    }

    /**
     * Tổng hợp số lượng action theo step. Dùng cho dashboard.
     *
     * @return array<string, array{created:int, updated:int, skipped:int, failed:int, last_run:int}>
     */
    public function summary(): array
    {
        $sql = "SELECT step, action, COUNT(*) AS cnt, MAX(run_time) AS last_run
                FROM {$this->logTable}
                GROUP BY step, action";
        $rows = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $out = [];
        foreach ($rows as $r) {
            $step = $r['step'];
            if (!isset($out[$step])) {
                $out[$step] = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'failed' => 0, 'last_run' => 0];
            }
            $out[$step][$r['action']] = (int) $r['cnt'];
            $out[$step]['last_run']   = max($out[$step]['last_run'], (int) $r['last_run']);
        }
        return $out;
    }

    /**
     * Lấy danh sách (db_id, db_table) đã được seeder TẠO MỚI ở step nhất định.
     * Dùng khi reset — chỉ xóa item do seeder tạo (action='created'), không đụng item user nhập tay.
     *
     * @return array<int, array{db_id:int, db_table:string, natural_key:string}>
     */
    public function getCreatedItems(string $step): array
    {
        $sql = "SELECT db_id, db_table, natural_key FROM {$this->logTable}
                WHERE step = :step AND action = :action
                ORDER BY id DESC";
        $sth = $this->db->prepare($sql);
        $sth->bindValue(':step',   $step);
        $sth->bindValue(':action', self::ACTION_CREATED);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Xóa toàn bộ log của 1 step (dùng khi reset xong).
     */
    public function clearStep(string $step): int
    {
        $sth = $this->db->prepare("DELETE FROM {$this->logTable} WHERE step = :step");
        $sth->bindValue(':step', $step);
        $sth->execute();
        return $sth->rowCount();
    }

    /**
     * Sinh UUID v4 (RFC 4122).
     */
    private function generateUuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
