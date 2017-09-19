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

ALTER TABLE `nv4_vi_laws_row` ADD `start_comm_time` INT(11) NULL AFTER `publtime`;
ALTER TABLE `nv4_vi_laws_row` ADD `end_comm_time` INT(11) NULL AFTER `start_comm_time`;