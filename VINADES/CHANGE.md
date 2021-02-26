### 26/2/2021
- Thêm mới trường `is_processed`,`processed_by`,`processed_time` vào bảng `nv4_vi_contact_send`

ALTER TABLE `nv4_vi_contact_send` ADD `is_processed` TINYINT(1) NOT NULL COMMENT 'Trạng thái xử lý' AFTER `is_reply`;
ALTER TABLE `nv4_vi_contact_send` ADD `processed_by` INT(11) NOT NULL COMMENT 'Xử lý bởi' AFTER `is_processed`, ADD `processed_time` INT(11) NOT NULL COMMENT 'Thời gian xử lý' AFTER `processed_by`;