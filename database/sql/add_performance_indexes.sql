-- ============================================================================
-- Performance indexes for the reports and the statistics dashboard.
--
-- Purely additive: no data is read, written or altered, only index structures
-- are created. Safe to run on a live database; on this dataset the whole script
-- completed in about 3 seconds.
--
-- Why this is needed
--   order_details holds 386,448 rows and had only a PRIMARY key, so every
--   report that joined it scanned the whole table. The factory tables
--   (operation_order_details / _results / _result_details) had no secondary
--   indexes at all, which made the dashboard's raw-material aggregate take
--   26 seconds on its own and fail outright over a full year.
--
-- Measured effect
--   dashboard, 3 months     27.1s  ->  1.10s
--   dashboard, 1 year       HTTP 500 after 98s   ->  2.22s
--   dashboard, all 8 years  HTTP 500 after 263s  ->  4.24s
--   item movements report   1.5s   ->  0.42s
--
-- NOTE: apply with a MySQL client, not `php artisan migrate`. `migrate:status`
-- on this project reports the earliest migrations as pending even though their
-- tables already exist, so running the migrator would try to recreate them.
-- ============================================================================

ALTER TABLE `order_details`                  ADD INDEX `idx_od_order_id`   (`order_id`);
ALTER TABLE `order_details`                  ADD INDEX `idx_od_item_store` (`item_id`, `store_id`);
ALTER TABLE `order_details`                  ADD INDEX `idx_od_store_id`   (`store_id`);

ALTER TABLE `operation_order_details`        ADD INDEX `idx_ood_op_order`  (`operation_order_id`);
ALTER TABLE `operation_order_details`        ADD INDEX `idx_ood_item`      (`item_id`);
ALTER TABLE `operation_order_details`        ADD INDEX `idx_ood_out_item`  (`out_item_id`);

ALTER TABLE `operation_order_results`        ADD INDEX `idx_oor_detail`    (`order_details_id`);
ALTER TABLE `operation_order_result_details` ADD INDEX `idx_oord_detail`   (`order_details_id`);

ALTER TABLE `operation_orders`               ADD INDEX `idx_oo_date_store` (`date`, `store_id`);

ALTER TABLE `orders`                         ADD INDEX `idx_o_date2_type`  (`date2`, `type`, `is_return`);

ALTER TABLE `accounts`                       ADD INDEX `idx_acc_lookup`
    (`accountable_type`, `accountable_id`, `type`, `pending`);


-- ---------------------------------------------------------------------------
-- Rollback
-- ---------------------------------------------------------------------------
-- ALTER TABLE `order_details`                  DROP INDEX `idx_od_order_id`;
-- ALTER TABLE `order_details`                  DROP INDEX `idx_od_item_store`;
-- ALTER TABLE `order_details`                  DROP INDEX `idx_od_store_id`;
-- ALTER TABLE `operation_order_details`        DROP INDEX `idx_ood_op_order`;
-- ALTER TABLE `operation_order_details`        DROP INDEX `idx_ood_item`;
-- ALTER TABLE `operation_order_details`        DROP INDEX `idx_ood_out_item`;
-- ALTER TABLE `operation_order_results`        DROP INDEX `idx_oor_detail`;
-- ALTER TABLE `operation_order_result_details` DROP INDEX `idx_oord_detail`;
-- ALTER TABLE `operation_orders`               DROP INDEX `idx_oo_date_store`;
-- ALTER TABLE `orders`                         DROP INDEX `idx_o_date2_type`;
-- ALTER TABLE `accounts`                       DROP INDEX `idx_acc_lookup`;
