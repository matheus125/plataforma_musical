CREATE TABLE users (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  username VARCHAR(255) UNIQUE NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  avatar_url VARCHAR(2048) NULL,
  locale VARCHAR(12) NOT NULL DEFAULT 'pt-BR',
  country CHAR(2) NULL,
  timezone VARCHAR(64) NOT NULL DEFAULT 'America/Sao_Paulo',
  signup_source VARCHAR(80) NULL,
  last_login_at TIMESTAMP NULL,
  billing_country CHAR(2) NULL,
  customer_provider_id VARCHAR(255) NULL,
  email_verified_at TIMESTAMP NULL,
  password VARCHAR(255) NOT NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE songs (
  id BIGSERIAL PRIMARY KEY,
  title VARCHAR(160) NOT NULL,
  slug VARCHAR(180) UNIQUE NULL,
  artist VARCHAR(160) NOT NULL,
  genre VARCHAR(80) NULL,
  language VARCHAR(40) NULL,
  key VARCHAR(12) NULL,
  musical_key VARCHAR(12) NULL,
  bpm SMALLINT NULL,
  difficulty_level SMALLINT NOT NULL DEFAULT 3 CHECK (difficulty_level BETWEEN 0 AND 5),
  audio_url VARCHAR(2048) NULL,
  cover_image_url VARCHAR(2048) NULL,
  source_type VARCHAR(40) NOT NULL DEFAULT 'independent',
  license_type VARCHAR(80) NULL,
  rights_holder VARCHAR(255) NULL,
  available_countries JSONB NULL,
  lyrics TEXT NULL,
  lyrics_language VARCHAR(12) NULL,
  vocal_range_min VARCHAR(8) NULL,
  vocal_range_max VARCHAR(8) NULL,
  is_active BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  deleted_at TIMESTAMP NULL
);

CREATE TABLE media_assets (
  id BIGSERIAL PRIMARY KEY,
  owner_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
  type VARCHAR(40) NOT NULL DEFAULT 'audio',
  disk VARCHAR(40) NOT NULL DEFAULT 'public',
  bucket VARCHAR(255) NULL,
  storage_key VARCHAR(2048) NOT NULL,
  public_url VARCHAR(2048) NULL,
  mime_type VARCHAR(120) NULL,
  size_bytes BIGINT NULL,
  checksum VARCHAR(128) NULL,
  duration_ms INTEGER NULL,
  sample_rate INTEGER NULL,
  channels SMALLINT NULL,
  bitrate INTEGER NULL,
  status VARCHAR(40) NOT NULL DEFAULT 'uploaded',
  metadata JSONB NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  deleted_at TIMESTAMP NULL
);

CREATE TABLE performances (
  id BIGSERIAL PRIMARY KEY,
  uuid UUID UNIQUE NULL,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  song_id BIGINT NOT NULL REFERENCES songs(id) ON DELETE CASCADE,
  media_asset_id BIGINT NULL REFERENCES media_assets(id) ON DELETE SET NULL,
  audio_url VARCHAR(2048) NOT NULL,
  status VARCHAR(40) NOT NULL DEFAULT 'published',
  visibility VARCHAR(40) NOT NULL DEFAULT 'public',
  published_at TIMESTAMP NULL,
  processing_started_at TIMESTAMP NULL,
  processing_finished_at TIMESTAMP NULL,
  processing_error TEXT NULL,
  analysis_version VARCHAR(40) NULL,
  duration_seconds INTEGER NULL,
  score_overall SMALLINT NULL CHECK (score_overall BETWEEN 0 AND 100),
  score_pitch SMALLINT NULL CHECK (score_pitch BETWEEN 0 AND 100),
  score_rhythm SMALLINT NULL CHECK (score_rhythm BETWEEN 0 AND 100),
  likes_count INTEGER NOT NULL DEFAULT 0,
  views_count INTEGER NOT NULL DEFAULT 0,
  is_public BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  deleted_at TIMESTAMP NULL
);

CREATE TABLE vocal_analyses (
  id BIGSERIAL PRIMARY KEY,
  performance_id BIGINT NOT NULL REFERENCES performances(id) ON DELETE CASCADE,
  media_asset_id BIGINT NULL REFERENCES media_assets(id) ON DELETE SET NULL,
  status VARCHAR(40) NOT NULL DEFAULT 'pending',
  model_name VARCHAR(255) NULL,
  model_version VARCHAR(80) NULL,
  analysis_version VARCHAR(40) NOT NULL DEFAULT 'v1-placeholder',
  analysis_started_at TIMESTAMP NULL,
  analysis_finished_at TIMESTAMP NULL,
  audio_duration_ms INTEGER NULL,
  sample_rate INTEGER NULL,
  channels SMALLINT NULL,
  bitrate INTEGER NULL,
  format VARCHAR(40) NULL,
  pitch_accuracy DECIMAL(5,2) NULL,
  rhythm_accuracy DECIMAL(5,2) NULL,
  timing_offset_ms DECIMAL(8,2) NULL,
  vibrato_score DECIMAL(5,2) NULL,
  breath_control_score DECIMAL(5,2) NULL,
  stability_score DECIMAL(5,2) NULL,
  range_score DECIMAL(5,2) NULL,
  pronunciation_score DECIMAL(5,2) NULL,
  energy_score DECIMAL(5,2) NULL,
  noise_level DECIMAL(8,3) NULL,
  clipping_detected BOOLEAN NOT NULL DEFAULT FALSE,
  silence_ratio DECIMAL(5,2) NULL,
  confidence_score DECIMAL(5,2) NULL,
  raw_analysis JSONB NULL,
  features_storage_key VARCHAR(2048) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE vocal_analysis_segments (
  id BIGSERIAL PRIMARY KEY,
  vocal_analysis_id BIGINT NOT NULL REFERENCES vocal_analyses(id) ON DELETE CASCADE,
  start_ms INTEGER NOT NULL,
  end_ms INTEGER NOT NULL,
  expected_pitch VARCHAR(12) NULL,
  detected_pitch VARCHAR(12) NULL,
  pitch_error_cents DECIMAL(8,2) NULL,
  timing_error_ms DECIMAL(8,2) NULL,
  segment_score DECIMAL(5,2) NULL,
  metadata JSONB NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE vocal_profiles (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
  vocal_range_min VARCHAR(8) NULL,
  vocal_range_max VARCHAR(8) NULL,
  comfortable_key VARCHAR(12) NULL,
  voice_type VARCHAR(40) NULL,
  current_level SMALLINT NOT NULL DEFAULT 1,
  goals JSONB NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE vocal_progress_snapshots (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  period_start DATE NOT NULL,
  period_end DATE NOT NULL,
  period_type VARCHAR(20) NOT NULL DEFAULT 'weekly',
  avg_score_overall DECIMAL(5,2) NULL,
  avg_score_pitch DECIMAL(5,2) NULL,
  avg_score_rhythm DECIMAL(5,2) NULL,
  stability_score DECIMAL(5,2) NULL,
  performances_count INTEGER NOT NULL DEFAULT 0,
  practice_seconds INTEGER NOT NULL DEFAULT 0,
  metadata JSONB NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE (user_id, period_start, period_type)
);

CREATE TABLE practice_sessions (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  song_id BIGINT NULL REFERENCES songs(id) ON DELETE SET NULL,
  performance_id BIGINT NULL REFERENCES performances(id) ON DELETE SET NULL,
  duration_seconds INTEGER NOT NULL DEFAULT 0,
  goal VARCHAR(120) NULL,
  metadata JSONB NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE analytics_events (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
  session_id VARCHAR(120) NULL,
  event_name VARCHAR(120) NOT NULL,
  entity_type VARCHAR(80) NULL,
  entity_id BIGINT NULL,
  metadata JSONB NULL,
  ip_hash VARCHAR(128) NULL,
  user_agent TEXT NULL,
  country CHAR(2) NULL,
  locale VARCHAR(12) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ranking_periods (
  id BIGSERIAL PRIMARY KEY,
  type VARCHAR(30) NOT NULL,
  scope VARCHAR(40) NOT NULL DEFAULT 'global',
  country CHAR(2) NULL,
  starts_at TIMESTAMP NOT NULL,
  ends_at TIMESTAMP NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'scheduled',
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE ranking_entries (
  id BIGSERIAL PRIMARY KEY,
  ranking_period_id BIGINT NOT NULL REFERENCES ranking_periods(id) ON DELETE CASCADE,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  song_id BIGINT NULL REFERENCES songs(id) ON DELETE SET NULL,
  score DECIMAL(8,2) NOT NULL DEFAULT 0,
  rank INTEGER NULL,
  performances_count INTEGER NOT NULL DEFAULT 0,
  metadata JSONB NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE (ranking_period_id, user_id, song_id)
);

CREATE TABLE challenges (
  id BIGSERIAL PRIMARY KEY,
  slug VARCHAR(255) NOT NULL UNIQUE,
  title VARCHAR(255) NOT NULL,
  description TEXT NULL,
  type VARCHAR(40) NOT NULL DEFAULT 'song',
  rules JSONB NULL,
  country CHAR(2) NULL,
  locale VARCHAR(12) NOT NULL DEFAULT 'pt-BR',
  starts_at TIMESTAMP NOT NULL,
  ends_at TIMESTAMP NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'draft',
  is_premium BOOLEAN NOT NULL DEFAULT FALSE,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  deleted_at TIMESTAMP NULL
);

CREATE TABLE challenge_songs (
  id BIGSERIAL PRIMARY KEY,
  challenge_id BIGINT NOT NULL REFERENCES challenges(id) ON DELETE CASCADE,
  song_id BIGINT NOT NULL REFERENCES songs(id) ON DELETE CASCADE,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE (challenge_id, song_id)
);

CREATE TABLE challenge_participants (
  id BIGSERIAL PRIMARY KEY,
  challenge_id BIGINT NOT NULL REFERENCES challenges(id) ON DELETE CASCADE,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  status VARCHAR(30) NOT NULL DEFAULT 'joined',
  joined_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE (challenge_id, user_id)
);

CREATE TABLE challenge_submissions (
  id BIGSERIAL PRIMARY KEY,
  challenge_id BIGINT NOT NULL REFERENCES challenges(id) ON DELETE CASCADE,
  performance_id BIGINT NOT NULL REFERENCES performances(id) ON DELETE CASCADE,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  score DECIMAL(8,2) NULL,
  submitted_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE (challenge_id, performance_id)
);

CREATE TABLE challenge_rewards (
  id BIGSERIAL PRIMARY KEY,
  challenge_id BIGINT NOT NULL REFERENCES challenges(id) ON DELETE CASCADE,
  type VARCHAR(40) NOT NULL,
  name VARCHAR(255) NOT NULL,
  payload JSONB NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE plans (
  id BIGSERIAL PRIMARY KEY,
  slug VARCHAR(255) NOT NULL UNIQUE,
  name VARCHAR(255) NOT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'BRL',
  price_cents INTEGER NOT NULL DEFAULT 0,
  billing_cycle VARCHAR(30) NOT NULL DEFAULT 'monthly',
  limits JSONB NULL,
  features JSONB NULL,
  is_active BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE subscriptions (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  plan_id BIGINT NULL REFERENCES plans(id) ON DELETE SET NULL,
  provider VARCHAR(40) NULL,
  provider_subscription_id VARCHAR(255) NULL,
  status VARCHAR(40) NOT NULL DEFAULT 'trialing',
  current_period_starts_at TIMESTAMP NULL,
  current_period_ends_at TIMESTAMP NULL,
  cancelled_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE payments (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  subscription_id BIGINT NULL REFERENCES subscriptions(id) ON DELETE SET NULL,
  provider VARCHAR(40) NOT NULL,
  provider_payment_id VARCHAR(255) NULL,
  amount_cents INTEGER NOT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'BRL',
  status VARCHAR(40) NOT NULL,
  paid_at TIMESTAMP NULL,
  metadata JSONB NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE entitlements (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  key VARCHAR(255) NOT NULL,
  value INTEGER NULL,
  expires_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE (user_id, key)
);

CREATE TABLE usage_limits (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  key VARCHAR(255) NOT NULL,
  used INTEGER NOT NULL DEFAULT 0,
  limit INTEGER NOT NULL DEFAULT 0,
  period_start DATE NOT NULL,
  period_end DATE NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE (user_id, key, period_start)
);
