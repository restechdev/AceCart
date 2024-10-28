INSERT INTO `business_settings` (`id`, `type`, `value`, `lang`, `created_at`, `updated_at`)
  VALUES (NULL, 'auction_section_bg_color', '#f7f9fa', NULL, current_timestamp(), current_timestamp()),
        (NULL, 'auction_content_bg_color', '#ffffff', NULL, current_timestamp(), current_timestamp()),
        (NULL, 'auction_section_outline', 0, NULL, current_timestamp(), current_timestamp()),
        (NULL, 'auction_section_outline_color', '#000000', NULL, current_timestamp(), current_timestamp());

COMMIT;