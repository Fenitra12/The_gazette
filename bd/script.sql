-- Schema + seed minimal (PostgreSQL)
-- ⚠️ Ce fichier est exécuté automatiquement via Docker (docker-entrypoint-initdb.d)

DROP TABLE IF EXISTS articles CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS authors CASCADE;
DROP TABLE IF EXISTS users CASCADE;

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'admin',
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(170) NOT NULL UNIQUE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE authors (
    id SERIAL PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190),
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE articles (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content TEXT NOT NULL,
    featured_image VARCHAR(255),
    category_id INT NOT NULL REFERENCES categories(id) ON DELETE RESTRICT,
    author_id INT NOT NULL REFERENCES authors(id) ON DELETE RESTRICT,
    is_featured BOOLEAN NOT NULL DEFAULT FALSE,
    status VARCHAR(20) NOT NULL DEFAULT 'draft',
    meta_title VARCHAR(255),
    meta_description VARCHAR(255),
    views INT NOT NULL DEFAULT 0,
    published_at TIMESTAMPTZ,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_articles_category_id ON articles(category_id);
CREATE INDEX idx_articles_author_id ON articles(author_id);
CREATE INDEX idx_articles_status_published ON articles(status, published_at);

-- Admin backoffice (email: admin@thegazette.local, password: admin123)
INSERT INTO users (email, password_hash, role)
VALUES ('admin@thegazette.local', '$2y$10$DRfzI7j/WlvGGuRSITie3uHfrrOuuqi1LK1V6QsN0iLtNFsLxpk7a', 'admin');

-- Seed minimal for FK used by articles inserts
INSERT INTO categories (id, name, slug) VALUES
    (1, 'Diplomatie', 'diplomatie'),
    (2, 'Humanitaire', 'humanitaire'),
    (3, 'Economie', 'economie'),
    (4, 'Médias', 'medias'),
    (5, 'Défense', 'defense'),
    (6, 'Cybersécurité', 'cybersecurite');

INSERT INTO authors (id, name, email) VALUES
    (1, 'Rédaction', NULL),
    (2, 'Agence Monde', NULL),
    (3, 'Reporter Terrain', NULL),
    (4, 'Analyste', NULL),
    (5, 'Économiste', NULL);

-- Resynchroniser les séquences après inserts avec id explicites
SELECT setval(pg_get_serial_sequence('users', 'id'), COALESCE((SELECT MAX(id) FROM users), 1), true);
SELECT setval(pg_get_serial_sequence('categories', 'id'), COALESCE((SELECT MAX(id) FROM categories), 1), true);
SELECT setval(pg_get_serial_sequence('authors', 'id'), COALESCE((SELECT MAX(id) FROM authors), 1), true);

INSERT INTO articles (
title, slug, excerpt, content, featured_image,
category_id, author_id,
is_featured, status,
meta_title, meta_description,
views, published_at
) VALUES

-- 1
(
'Diplomatic Talks Between Major Powers Begin',
'diplomatic-talks-major-powers',
'International leaders start negotiations to reduce tensions.',
'The Iran conflict remains at the center of global diplomatic talks as major powers begin negotiations to reduce Middle East tensions. This geopolitical crisis has prompted urgent discussions between world leaders seeking stability.

The Iran conflict escalation has forced countries to reconsider alliances, economic strategies, and security priorities. Middle East tensions linked to the Iran conflict continue to influence diplomatic agendas across global institutions.

Negotiations focus on de-escalation strategies, economic sanctions, and long-term peace frameworks. The geopolitical crisis surrounding the Iran conflict requires coordinated international responses to avoid further instability.

Experts highlight that diplomatic engagement is essential in resolving the Iran conflict. Middle East tensions can only be reduced through dialogue, cooperation, and sustained political commitment.

The outcome of these diplomatic talks will significantly impact the future of the Iran conflict and global geopolitical stability.',
'diplomacy1.jpg',
1, 2,
false, 'published',
'Diplomatic Talks Global Powers',
'Global diplomatic efforts addressing Iran conflict and Middle East tensions',
2100, NOW()
),

-- 2
(
'Civilian Impact in Conflict Zones fenitra',
'civilian-impact-conflict-zones',
'Reports highlight humanitarian concerns in affected regions.',
'The Iran Fenitra conflict escalation is having a profound humanitarian impact as civilian populations face increasing challenges. Middle East tensions are affecting daily life, economic stability, and access to essential services.

The geopolitical crisis surrounding the Iran conflict has created uncertainty and fear among local communities. Reports indicate displacement, resource shortages, and growing humanitarian needs.

International organizations are calling for urgent intervention to address the humanitarian consequences of the Iran conflict. Middle East tensions must be managed to prevent further suffering.

Experts emphasize that resolving the Iran conflict is critical not only for political stability but also for humanitarian protection. The geopolitical crisis continues to highlight the importance of global cooperation.

The civilian impact of the Iran conflict remains a key concern for policymakers and humanitarian agencies worldwide.',
'civilian1.jpg',
2, 4,
false, 'published',
'Humanitarian Crisis Update',
'Impact of Iran conflict on civilians and humanitarian conditions',
2600, NOW()
),

-- 3
(
'International Sanctions Discussions Increase',
'international-sanctions-discussions',
'Countries consider new economic sanctions.',
'The Iran conflict escalation has intensified discussions حول international sanctions as countries evaluate economic responses to Middle East tensions.

Sanctions are a key tool in addressing geopolitical crisis scenarios like the Iran conflict. Governments are analyzing their potential impact on economic stability and diplomatic relations.

The geopolitical crisis linked to the Iran conflict requires careful balancing between pressure and diplomacy. Middle East tensions continue to influence policy decisions.

Experts argue that sanctions may affect both regional economies and global markets. The Iran conflict remains a central factor in shaping these strategies.

The effectiveness of sanctions in resolving the Iran conflict remains uncertain, highlighting the complexity of the geopolitical crisis.',
'sanctions1.jpg',
1, 5,
false, 'published',
'Sanctions Political News',
'International sanctions related to Iran conflict and geopolitical crisis',
1700, NOW()
),

-- 4
(
'Media Coverage of Iran Crisis Expands',
'media-coverage-iran-crisis-expands',
'Global media intensifies coverage of ongoing conflict.',
'Media coverage of the Iran conflict has expanded significantly as Middle East tensions continue to rise. The geopolitical crisis is receiving global attention across news platforms.

The Iran conflict is shaping public perception, influencing political discourse, and driving global awareness. Media narratives play a key role in understanding the geopolitical crisis.

Coverage of Middle East tensions highlights the complexity of the Iran conflict and its global implications. Journalists and analysts provide insights into developments and future scenarios.

The influence of media on the Iran conflict cannot be underestimated. It shapes opinions, informs decisions, and contributes to international dialogue.

As the geopolitical crisis evolves, media coverage will remain essential in tracking the Iran conflict and Middle East tensions.',
'media1.jpg',
4, 2,
false, 'published',
'Media Coverage Iran Conflict',
'Global media analysis of Iran conflict and Middle East tensions',
1600, NOW()
),

-- 5
(
'Military Strategies Shift in Middle East',
'military-strategies-shift-middle-east',
'Defense strategies evolve amid rising tensions.',
'The Iran conflict escalation is driving significant changes in military strategies across the Middle East. Countries are adapting their defense systems in response to geopolitical crisis developments.

Middle East tensions linked to the Iran conflict are influencing military planning, resource allocation, and technological investments. Strategic positioning is becoming increasingly important.

The geopolitical crisis has led to increased collaboration among allies and enhanced readiness across military forces. The Iran conflict remains a key driver of these changes.

Experts note that evolving military strategies reflect the uncertainty of the Iran conflict. Middle East tensions require continuous adaptation.

The future of military dynamics in the region will depend on how the Iran conflict develops and how geopolitical risks are managed.',
'military1.jpg',
5, 3,
false, 'published',
'Military Strategy Middle East',
'Military response to Iran conflict and regional tensions',
2800, NOW()
),

-- 6
(
'Global Trade Disruptions Expected',
'global-trade-disruptions-expected',
'Supply chains face uncertainty due to conflict.',
'The Iran conflict escalation is expected to disrupt global trade as Middle East tensions impact supply chains. The geopolitical crisis is affecting transportation routes and economic exchanges.

Trade disruptions linked to the Iran conflict could have long-term consequences for global markets. Businesses are adapting to uncertainty and adjusting strategies.

The geopolitical crisis highlights the interconnected nature of global trade systems. The Iran conflict plays a significant role in shaping these dynamics.

Experts warn that prolonged Middle East tensions could lead to sustained disruptions. The Iran conflict remains a key factor in global economic stability.

Addressing trade challenges will require coordinated efforts to resolve the geopolitical crisis.',
'trade1.jpg',
3, 2,
false, 'published',
'Global Trade Impact',
'Impact of Iran conflict on global trade and supply chains',
2000, NOW()
),

-- 7
(
'Cybersecurity Threats Increase Amid Conflict',
'cybersecurity-threats-increase-conflict',
'Digital threats rise alongside geopolitical tensions.',
'The Iran conflict escalation is contributing to an increase in cybersecurity threats as Middle East tensions extend into the digital domain.

The geopolitical crisis has created new risks for critical infrastructure, financial systems, and communication networks. The Iran conflict is influencing cyber strategies.

Experts highlight the growing importance of cybersecurity in managing geopolitical crisis scenarios. Middle East tensions now include digital warfare elements.

The Iran conflict demonstrates how modern conflicts extend beyond physical boundaries. Cybersecurity is becoming a central concern.

As the geopolitical crisis evolves, addressing cyber threats linked to the Iran conflict will be essential.',
'cyber1.jpg',
6, 1,
false, 'published',
'Cybersecurity Conflict Risks',
'Cyber threats linked to Iran conflict and geopolitical tensions',
1900, NOW()
),

-- 8
(
'Refugee Movements Increase in Region',
'refugee-movements-increase-region',
'Population displacement rises due to instability.',
'The Iran conflict escalation is contributing to increased refugee movements across the Middle East. Rising tensions are forcing populations to seek safety.

The geopolitical crisis is creating humanitarian challenges as countries manage migration flows. The Iran conflict remains a key factor.

Experts emphasize the need for coordinated responses to address displacement caused by Middle East tensions.

The Iran conflict highlights the human cost of geopolitical crises and the importance of global cooperation.',
'refugee1.jpg',
2, 3,
false, 'published',
'Refugee Crisis Middle East',
'Migration impact of Iran conflict and regional instability',
2300, NOW()
),

-- 9
(
'Defense Budgets Rise Worldwide',
'defense-budgets-rise-worldwide',
'Countries increase military spending.',
'The Iran conflict escalation is influencing defense budgets worldwide as governments respond to Middle East tensions.

The geopolitical crisis has prompted increased military spending and strategic investments. The Iran conflict is a key driver.

Experts note that defense budgets reflect broader geopolitical concerns and security priorities.',
'defense1.jpg',
5, 4,
false, 'published',
'Defense Spending Global',
'Impact of Iran conflict on global defense budgets',
2100, NOW()
),

-- 10
(
'Airspace Restrictions Impact Travel',
'airspace-restrictions-impact-travel',
'Flights disrupted due to regional tensions.',
'The Iran conflict escalation has led to airspace restrictions affecting global travel. Middle East tensions are disrupting aviation routes.

The geopolitical crisis is creating challenges for airlines and passengers. The Iran conflict remains central.

Travel disruptions highlight the broader impact of geopolitical tensions on global mobility.',
'air1.jpg',
3, 1,
false, 'published',
'Air Travel Disruptions',
'Impact of Iran conflict on global aviation and travel',
1800, NOW()
),

-- 11
(
'Technology Markets React to Crisis',
'technology-markets-react-crisis',
'Tech sector faces uncertainty amid tensions.',
'Technology markets are reacting to the Iran conflict escalation as Middle East tensions influence innovation and investment.

The geopolitical crisis is affecting supply chains and digital infrastructure. The Iran conflict is shaping market trends.',
'tech1.jpg',
6, 2,
false, 'published',
'Tech Market Impact',
'Technology sector response to Iran conflict and tensions',
1700, NOW()
),

-- 12
(
'Regional Alliances Strengthen',
'regional-alliances-strengthen',
'Countries form stronger partnerships.',
'The Iran conflict escalation is encouraging stronger regional alliances as Middle East tensions persist.

The geopolitical crisis is reshaping diplomatic relationships. The Iran conflict plays a key role in alliance formation.',
'alliance1.jpg',
1, 3,
false, 'published',
'Regional Alliances News',
'Alliances influenced by Iran conflict and geopolitical crisis',
2000, NOW()
),

-- 13
(
'Shipping Routes Under Threat',
'shipping-routes-under-threat',
'Maritime trade faces new risks.',
'The Iran conflict escalation threatens key shipping routes as Middle East tensions rise.

The geopolitical crisis is impacting maritime trade and global logistics. The Iran conflict is central to these risks.',
'shipping1.jpg',
3, 4,
false, 'published',
'Shipping Route Risks',
'Impact of Iran conflict on global shipping and logistics',
2200, NOW()
),

-- 14
(
'Economic Forecasts Revised Globally',
'economic-forecasts-revised-globally',
'Economists adjust predictions due to instability.',
'The Iran conflict escalation is forcing economists to revise global forecasts as Middle East tensions persist.

The geopolitical crisis is influencing growth projections, inflation, and policy decisions. The Iran conflict remains key.',
'economy1.jpg',
3, 5,
false, 'published',
'Economic Forecast Update',
'Global economic outlook impacted by Iran conflict',
2500, NOW()
),

-- 15
(
'International Aid Efforts Expand',
'international-aid-efforts-expand',
'Global support increases for affected regions.',
'International aid efforts are expanding in response to the Iran conflict escalation and Middle East tensions.

The geopolitical crisis has increased humanitarian needs. The Iran conflict is driving global support initiatives.

Organizations are mobilizing resources to address the impact of the conflict and support affected populations.',
'aid1.jpg',
2, 1,
false, 'published',
'International Aid News',
'Humanitarian response to Iran conflict and regional crisis',
2600, NOW()
);