replace into `sys_template` (`uid`, `pid`, `title`, `root`, `clear`, `config`, `constants`, `deleted`, `hidden`)
values (1, 1, 'Root Template', 1, 3,
'@import "EXT:fluid_styled_content/Configuration/TypoScript/setup.typoscript"
@import "EXT:bw_static_template/Configuration/TypoScript/setup.typoscript"

page = PAGE
page.typeNum = 0
page.10 < styles.content.get',
'plugin.tx_bwstatictemplate.view.templateRootPath = EXT:bw_static_template/Resources/Private/Templates/',
0, 0);
