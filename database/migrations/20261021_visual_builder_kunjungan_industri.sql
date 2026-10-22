-- Register Kunjungan Industri in Visual Builder. Safe to run more than once.
-- This does not delete or alter existing revisions or school content.
INSERT INTO visual_builder_pages (page_key, page_label)
VALUES ('kunjungan-industri', 'Kunjungan Industri')
ON DUPLICATE KEY UPDATE page_label = VALUES(page_label);
