INSERT INTO `nv4_config` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'allow_null_origin', '0');
INSERT INTO `nv4_config` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'ip_allow_null_origin', '');
INSERT INTO `nv4_config` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'site', 'nv_csp', 'script-src &#039;self&#039; *.google.com *.google-analytics.com *.googletagmanager.com *.gstatic.com *.facebook.com *.facebook.net *.twitter.com *.zalo.me *.zaloapp.com &#039;unsafe-inline&#039; &#039;unsafe-eval&#039;;style-src &#039;self&#039; *.google.com &#039;unsafe-inline&#039;;frame-src &#039;self&#039; *.google.com *.youtube.com *.facebook.com *.facebook.net *.twitter.com *.zalo.me;base-uri &#039;self&#039;;');
INSERT INTO `nv4_config` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'site', 'nv_csp_act', '1');
INSERT INTO `nv4_config` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'site', 'nv_rp', 'no-referrer-when-downgrade, strict-origin-when-cross-origin');
INSERT INTO `nv4_config` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'site', 'nv_rp_act', '1');
INSERT INTO `nv4_config` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'cookie_SameSite', 'Lax');
ALTER TABLE `nv4_users_field` CHANGE COLUMN `match_type` `match_type` ENUM('none','alphanumeric','unicodename','email','url','regex','callback') NOT NULL DEFAULT 'none' AFTER `sql_choices`;
UPDATE `nv4_users_field` SET `match_type`='unicodename' WHERE  `field` IN ('first_name','last_name');
