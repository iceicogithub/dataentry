05/12/2023 :
ALTER TABLE `acts` ADD `act_no` VARCHAR(255) NULL AFTER `act_content`, 
ADD `act_date` VARCHAR(255) NULL AFTER `act_no`, ADD `act_description` VARCHAR(500) NULL AFTER `act_date`; (done)

ALTER TABLE `section` ADD `section_no` INT(11) NULL AFTER `section_id`;(done)
ALTER TABLE `footnote` ADD `section_no` INT(11) NULL AFTER `section_id`;(done)
ALTER TABLE `sub_section` ADD `section_no` INT(11) NULL AFTER `section_id`;(done)
ALTER TABLE `footnote` ADD `parts_id` INT(11) NULL AFTER `chapter_id`;(done)
ALTER TABLE `sub_section` ADD `parts_id` INT(11) NULL AFTER `chapter_id`;(done)