replace into `tt_content` (`uid`, `pid`, `header`, `CType`, `tx_bwstatictemplate_template_path`, `tx_bwstatictemplate_json`, `assets`, `sys_language_uid`, `l18n_parent`, `colPos`)
values (1, 2, '', 'bw_static_template', '', null, 0, 0, 0, 0),
			 (2, 3, 'EXT Path Template', 'bw_static_template', 'EXT:bw_static_template/Tests/Fixtures/Templates/TestTemplate.html', null, 0, 0, 0, 0),
			 (3, 4, 'Template Name Only', 'bw_static_template', 'TestTemplate', null, 0, 0, 0, 0),
			 (4, 5, 'JSON Variables', 'bw_static_template', 'JsonTemplate', '{"headline":"Hello from JSON","color":"blue"}', 0, 0, 0, 0),
			 (5, 6, 'File Injection', 'bw_static_template', 'FilesTemplate', null, 1, 0, 0, 0),
			 (6, 7, 'Invalid JSON', 'bw_static_template', 'JsonTemplate', '{not valid json}', 0, 0, 0, 0);
