ALTER TABLE  `comments_notifications` ADD  `created` INT UNSIGNED NOT NULL
ALTER TABLE  `messages_notifications` ADD  `created` INT UNSIGNED NOT NULL
RENAME TABLE  `chocobo-riding`.`comments_notifications` TO  `chocobo-riding`.`comment_notifications` ;
RENAME TABLE  `chocobo-riding`.`messages_notifications` TO  `chocobo-riding`.`message_notifications` ;
ALTER TABLE  `comment_notifications` ADD  `topic_id` INT UNSIGNED NOT NULL AFTER  `id`
ALTER TABLE  `message_notifications` ADD  `discussion_id` INT UNSIGNED NOT NULL AFTER  `id`
