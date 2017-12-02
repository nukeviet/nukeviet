# Module laws

Văn bản pháp quy

```
#lệnh thêm bảng nv4_vi_laws_admins
CREATE TABLE `nv4_vi_laws_admins` (
  `userid` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `subjectid` smallint(4) NOT NULL DEFAULT '0',
  `admin` tinyint(4) NOT NULL DEFAULT '0',
  `add_content` tinyint(4) NOT NULL DEFAULT '0',
  `edit_content` tinyint(4) NOT NULL DEFAULT '0',
  `del_content` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `nv4_vi_laws_admins` ADD UNIQUE KEY `userid` (`userid`,`subjectid`);
```