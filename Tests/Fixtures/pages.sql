replace into `pages` (`uid`, `pid`, `title`, `slug`, `sys_language_uid`, `l10n_parent`, `l10n_source`, `perms_userid`, `perms_groupid`, `perms_user`, `perms_group`, `perms_everybody`, `doktype`, `is_siteroot`)
values (1, 0, 'Root', '/', 0, 0, 0, 1, 1, 31, 31, 1, 1, 1),
       (2, 1, 'Invalid Template', '/invalid-template/', 0, 0, 0, 1, 1, 31, 31, 1, 1, 0),
       (3, 1, 'EXT Path Template', '/ext-path-template/', 0, 0, 0, 1, 1, 31, 31, 1, 1, 0),
       (4, 1, 'Template Name Only', '/template-name-only/', 0, 0, 0, 1, 1, 31, 31, 1, 1, 0),
       (5, 1, 'JSON Variables', '/json-variables/', 0, 0, 0, 1, 1, 31, 31, 1, 1, 0),
       (6, 1, 'File Injection', '/file-injection/', 0, 0, 0, 1, 1, 31, 31, 1, 1, 0);
