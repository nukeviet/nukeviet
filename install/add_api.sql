CREATE TABLE nv4_authors_api_role (
    role_id smallint(4) NOT NULL AUTO_INCREMENT,
    role_title varchar(250) NOT NULL DEFAULT '',
    role_description text NOT NULL,
    role_data text NOT NULL,
    addtime int(11) NOT NULL DEFAULT '0',
    edittime int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (role_id)
) ENGINE = MYISAM COMMENT 'Bảng lưu quyền truy cập API';

CREATE TABLE nv4_authors_api_credential (
    admin_id int(11) UNSIGNED NOT NULL,
    credential_title varchar(255) NOT NULL DEFAULT '',
    credential_ident varchar(50) NOT NULL DEFAULT '',
    credential_secret varchar(255) NOT NULL DEFAULT '',
    credential_ips varchar(255) NOT NULL DEFAULT '',
    api_roles varchar(255) NOT NULL DEFAULT '',
    addtime int(11) NOT NULL DEFAULT '0',
    edittime int(11) NOT NULL DEFAULT '0',
    last_access int(11) NOT NULL DEFAULT '0',
    UNIQUE KEY credential_ident (credential_ident),
    UNIQUE KEY credential_secret (credential_secret(191)),
    KEY admin_id (admin_id)
) ENGINE = MYISAM COMMENT 'Bảng lưu key API của quản trị';

INSERT INTO
    `nv4_config` (`lang`, `module`, `config_name`, `config_value`)
VALUES
    ('sys', 'global', 'remote_api_access', '0');

INSERT INTO
    `nv4_config` (`lang`, `module`, `config_name`, `config_value`)
VALUES
    ('sys', 'global', 'remote_api_log', '1');