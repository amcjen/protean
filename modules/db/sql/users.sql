SET FOREIGN_KEY_CHECKS=0;
INSERT INTO `auth_role` (`auth_role_id`, `name`, `role_key`, `status`, `expires`, `created_at`, `updated_at`) VALUES 
(1, 'Administration', 'admin', 'A', NULL, now(), now());

INSERT INTO `auth_user` (`auth_user_id`, `username`, `password`, `last_login`, `last_login_from`, `last_password_change`, `status`, `expires`, `party_id`, `created_at`, `updated_at`) VALUES 
(1, 'admin', '3b43058f6d5aa2540b1353165603cb36', NULL, NULL, NULL, 'A', NULL, 1, now(), now());

INSERT INTO `auth_user_group` (`auth_user_group_id`, `name`, `group_key`, `status`, `expires`, `created_at`, `updated_at`) VALUES 
(1, 'Administration', 'admin', 'A', NULL, now(), now());

INSERT INTO `auth_user_group_role_xref` (`auth_user_group_id`, `auth_role_id`) VALUES 
(1, 1);

INSERT INTO `auth_user_group_xref` (`auth_user_id`, `auth_user_group_id`) VALUES 
(1, 1);

INSERT INTO `locale_location` (`locale_location_id`, `name`, `address_1`, `address_2`, `address_3`, `city`, `locale_region_id`, `postal_code`, `locale_country_id`, `telephone_voice`, `telephone_fax`, `latitude`, `longitude`, `altitude`) VALUES 
(1, 'Billing Address', 'P.O. Box 123', '', NULL, 'Reno', 37, '89501', 226, '775-555-1212', '775-555-1212', NULL, NULL, NULL);

INSERT INTO `party` (`party_id`, `first_name`, `middle_name`, `last_name`, `prefix`, `suffix`, `birthday`, `timezone`, `url`, `organization_name`, `is_company`, `title`, `locale_location_id_billing`, `locale_location_id_shipping`, `telephone_cell`, `telephone_work`, `email`, `im_username`, `im_type`, `created_at`, `updated_at`, `note`) VALUES 
(1, 'Admin', NULL, 'User', NULL, NULL, NULL, NULL, NULL, '', 0, NULL, 1, 1, NULL, NULL, 'me@here.com', NULL, NULL, now(), now(), NULL);
SET FOREIGN_KEY_CHECKS=1;
