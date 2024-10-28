ALTER TABLE `club_point_details` 
    ADD `converted_amount` DOUBLE(25,2) NULL DEFAULT '0.00' AFTER `point`,
    ADD `refunded` INT(1) NOT NULL DEFAULT '0' AFTER `converted_amount`;

COMMIT;