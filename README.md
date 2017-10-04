# Nâng cấp module lên phiên bản 4.0.27
1. Cập nhật code mới
2. Truy cập đường dẫn http://domain/laws.to.4.0.27 đợi cho đến khi nhận đuợc thông báo "OK" là thành công.

#Câu lênh chạy để thêm phần comment cho module laws
INSERT INTO `laws_my`.`nv4_config` (`lang`, `module`, `config_name`, `config_value`) VALUES
('vi', 'laws', 'view_comm', '6'),
('vi', 'laws', 'allowed_comm', '6'),
('vi', 'laws', 'auto_postcomm', '0'),
('vi', 'laws', 'setcomm', '4'),
('vi', 'laws', 'activecomm', '0'),
('vi', 'laws', 'emailcomm', '0'),
('vi', 'laws', 'adminscomm', ''),
('vi', 'laws', 'sortcomm', '0'),
('vi', 'laws', 'captcha', '1'),
('vi', 'laws', 'perpagecomm', '5'),
('vi', 'laws', 'timeoutcomm', '360');

CREATE TABLE IF NOT EXISTS `nv4_vi_laws_examine` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` smallint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `nv4_vi_laws_examine`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `nv4_vi_laws_row` ADD `start_comm_time` INT(11) NULL AFTER `publtime`;
ALTER TABLE `nv4_vi_laws_row` ADD `end_comm_time` INT(11) NULL AFTER `start_comm_time`;
ALTER TABLE `nv4_vi_laws_row` ADD `eid` INT(11) NULL DEFAULT '0' AFTER `sid`;
ALTER TABLE `nv4_vi_laws_row` ADD `approval` TINYINT(1) NOT NULL DEFAULT '0' AFTER `status`;//trạng thái đã thông qua/chưa thông qua