05/12/2023 :
ALTER TABLE `acts` ADD `act_no` VARCHAR(255) NULL AFTER `act_content`, ADD `act_date` VARCHAR(255) NULL AFTER `act_no`, ADD `act_description` VARCHAR(500) NULL AFTER `act_date`;