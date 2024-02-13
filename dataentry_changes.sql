05/12/2023 :
ALTER TABLE `acts` ADD `act_no` VARCHAR(255) NULL AFTER `act_content`, 
ADD `act_date` VARCHAR(255) NULL AFTER `act_no`, ADD `act_description` VARCHAR(500) NULL AFTER `act_date`; (done)

ALTER TABLE `section` ADD `section_no` INT(11) NULL AFTER `section_id`;(done)
ALTER TABLE `footnote` ADD `section_no` INT(11) NULL AFTER `section_id`;(done)
ALTER TABLE `sub_section` ADD `section_no` INT(11) NULL AFTER `section_id`;(done)
ALTER TABLE `footnote` ADD `parts_id` INT(11) NULL AFTER `chapter_id`;(done)
ALTER TABLE `sub_section` ADD `parts_id` INT(11) NULL AFTER `chapter_id`;(done)

ALTER TABLE `acts` ADD `act_footnote_title` VARCHAR(255) NULL AFTER `act_description`, ADD `act_footnote_description` VARCHAR(2555) NULL AFTER `act_footnote_title`;(done)

ALTER TABLE `sub_section` ADD `sub_section_no` INT(11) NULL AFTER `sub_section_id`;(done)

-- new changes
ALTER TABLE `acts` ADD `act_summary` VARCHAR(500) NULL AFTER `act_footnote_description`;(done)

-- ********************************new changes ************************* 
ALTER TABLE `section` CHANGE `section_no` `section_no` VARCHAR(11) NULL DEFAULT NULL;(done)

ALTER TABLE `section` ADD `section_rank` VARCHAR(11) NULL AFTER `section_id`;(done)

rules table ;(done)
ALTER TABLE `footnote` ADD `rule_id` INT(11) NULL AFTER `regulation_no`, ADD `rule_no` VARCHAR(255) NULL AFTER `rule_id`;(done)

ALTER TABLE `section` CHANGE `section_rank` `section_rank` INT(11) NULL DEFAULT NULL;(done)

ALTER TABLE `footnote` ADD `sub_section_id` INT NULL AFTER `section_no`;(done)

ALTER TABLE `acts` CHANGE `act_footnote_description` `act_footnote_description` VARCHAR(25555) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;(done)

ALTER TABLE `rules` ADD `schedule_id` INT(11) NULL AFTER `parts_id`;(done)

ALTER TABLE `footnote` ADD `schedule_id` INT(11) NULL AFTER `priliminary_id`;(done)

ALTER TABLE `rules` ADD `rule_rank` INT(11) NULL AFTER `rule_no`;(done)

ALTER TABLE `rules` CHANGE `rule_no` `rule_no` VARCHAR(255) NULL DEFAULT NULL;(done)

new table of subrules 

ALTER TABLE `footnote` ADD `sub_rule_id` INT(11) NULL AFTER `rule_no`;(done)

[appedices table   
articles table 
sub_articles table 
subTYPES table 
ALTER TABLE `footnote` ADD `article_id` INT(11) NULL AFTER `sub_rule_id`, ADD `article_no` INT(11) NULL AFTER `article_id`, ADD `sub_article_id` INT(11) NULL AFTER `article_no`;
ALTER TABLE `footnote` ADD `appendices_id` INT(11) NULL AFTER `schedule_id`;](done)

INSERT INTO `subtypes` (`subtypes_id`, `type`) VALUES (NULL, 'ORDER'), (NULL, 'ANNEXTURE');(done)
-- 13/02/2024
[
ALTER TABLE `acts` ADD `enactment_date` DATE NULL DEFAULT NULL AFTER `act_date`, ADD `enforcement_date` DATE NULL DEFAULT NULL AFTER `enactment_date`;
ALTER TABLE `acts` ADD `ministry` VARCHAR(1255) NULL AFTER `act_content`;


ALTER TABLE `section` ADD `appendices_id` INT(11) NULL DEFAULT NULL AFTER `maintype_id`, ADD `schedule_id` INT(11) NULL DEFAULT NULL AFTER `appendices_id`;
ALTER TABLE `rules` ADD `appendices_id` INT(11) NULL DEFAULT NULL AFTER `maintype_id`, ADD `priliminary_id` INT(11) NULL DEFAULT NULL AFTER `appendices_id`;
ALTER TABLE `regulations` ADD `regulation_rank` VARCHAR(255) NULL DEFAULT NULL AFTER `regulation_no`, ADD `appendices_id` INT(11) NULL DEFAULT NULL AFTER `regulation_rank`, ADD `schedule_id` INT(11) NULL DEFAULT NULL AFTER `appendices_id`, ADD `priliminary_id` INT(11) NULL DEFAULT NULL AFTER `schedule_id`;

[
list,
part,
appendix
order,
annexture
this above table would be added 
]    ](done)