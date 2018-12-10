CREATE UNIQUE INDEX IF NOT EXISTS vehicle_brand ON vehicle_brand (title);
INSERT INTO vehicle_brand (id, title) VALUES ('5d592299-82ed-46e4-b605-4599a47c213c', 'BMW') ON CONFLICT DO NOTHING;
INSERT INTO vehicle_brand (id, title) VALUES ('bb306f48-142a-4dc0-82d0-bae6d6ce57ad', 'Mercedes-Benz') ON CONFLICT DO NOTHING;
INSERT INTO vehicle_brand (id, title) VALUES ('dce1390a-cdef-46f5-92c6-c2aacafff3da', 'Honda') ON CONFLICT DO NOTHING;
INSERT INTO vehicle_brand (id, title) VALUES ('49063d56-5686-4d19-b383-4e7428c2e796', 'Hummer')ON CONFLICT DO NOTHING;
INSERT INTO vehicle_brand (id, title) VALUES ('b51ada61-cfd3-4518-8383-7e5186760a91', 'Audi') ON CONFLICT DO NOTHING;;
INSERT INTO vehicle_brand (id, title) VALUES ('759529ea-27a2-4483-a27b-ed113d554a0f', 'Bentley') ON CONFLICT DO NOTHING;;
