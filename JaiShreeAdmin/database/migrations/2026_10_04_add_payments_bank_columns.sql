-- Migration: Add bank transfer columns and nullable defaults to `payments` table
-- Required by Admin::wallet_details() and Api_model::get_qr_details()

ALTER TABLE `payments`
  ADD COLUMN `account_number` VARCHAR(100) DEFAULT NULL AFTER `upi_num`,
  ADD COLUMN `ifsc_code` VARCHAR(50) DEFAULT NULL AFTER `account_number`,
  ADD COLUMN `bank_name` VARCHAR(100) DEFAULT NULL AFTER `ifsc_code`,
  ADD COLUMN `account_holder_name` VARCHAR(100) DEFAULT NULL AFTER `bank_name`,
  MODIFY COLUMN `name` VARCHAR(100) DEFAULT '',
  MODIFY COLUMN `upi_num` VARCHAR(100) DEFAULT '',
  MODIFY COLUMN `qr_img` TEXT DEFAULT NULL,
  MODIFY COLUMN `upi_company_img` VARCHAR(100) DEFAULT '';
