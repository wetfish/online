-- This isn't a complete install! This just patches the tables added
-- by smf. Does not include wfo tables.

ALTER TABLE `smf_boards`  ADD `coins_per_post` INT(11) NOT NULL ;
ALTER TABLE `smf_members`  ADD `coins` INT(11) NOT NULL ,  ADD `last_feature_purchase` INT(10) NOT NULL ;
