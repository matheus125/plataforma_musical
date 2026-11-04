# Architecture Foundation

This document describes the prepared foundation for future AI vocal analysis, rankings, challenges, monetization, analytics, and global expansion.

## Current Boundary

The platform remains a Laravel API plus Next.js PWA. Real vocal AI, real billing providers, real challenge UX, and real ranking screens are intentionally not implemented yet.

## Domain Foundation

- `MediaAsset` separates uploaded files from `Performance`.
- `VocalAnalysis` separates AI output from publication and feed data.
- `RankingPeriod` and `RankingEntry` prepare weekly/monthly/global rankings.
- `Challenge`, participants, songs, submissions, and rewards prepare competitive challenges.
- `VocalProfile`, progress snapshots, and practice sessions prepare long-term vocal evolution.
- `Plan`, `Subscription`, `Payment`, `Entitlement`, and `UsageLimit` prepare monetization.
- `AnalyticsEvent` prepares product analytics without overloading counters on core tables.
- `SongTranslation` and user locale/country/timezone fields prepare internationalization.

## Upload Pipeline

Current upload flow:

1. `PublishPerformanceAction` validates song and user context.
2. `MediaStorageService` stores the audio file and creates `media_assets`.
3. `Performance` is created with temporary scores and linked to the media asset.
4. `PerformanceUploaded` event is dispatched.
5. `ProcessUploadedAudioJob` marks media as processed.
6. `RunVocalAnalysisJob` creates a placeholder `vocal_analyses` row.

Future production flow:

1. Frontend requests signed upload URL.
2. Client uploads directly to S3/R2.
3. Backend receives media completion callback.
4. Worker validates, transcodes, generates waveform, extracts features.
5. Vocal AI creates detailed analysis rows and segments.
6. Ranking and progress jobs update derived tables.

## Service Layer

- `MediaStorageService`: storage abstraction for local/S3-compatible disks.
- `AudioProcessingService`: placeholder for transcoding, waveform and metadata extraction.
- `VocalAnalysisService`: placeholder for real model execution.
- `RankingService`: recalculates weekly ranking snapshots.
- `ChallengeService`: challenge participation and leaderboard hooks.
- `EntitlementService`: billing feature access foundation.
- `AnalyticsService`: event ingestion.

## Jobs

- `ProcessUploadedAudioJob`
- `GenerateWaveformJob`
- `RunVocalAnalysisJob`
- `UpdateWeeklyRankingJob`
- `UpdateChallengeLeaderboardJob`
- `TrackAnalyticsEventJob`

All jobs should remain idempotent as real processors are added.

## Events

- `PerformanceUploaded`
- `PerformanceAnalysisCompleted`
- `RankingRecalculated`
- `ChallengeJoined`

These events are the preferred integration points for notifications, analytics, feed fanout, and future gamification.

## Next Architectural Priorities

1. Add API v1 namespace before public integrations.
2. Add policies and roles before expanding admin features.
3. Move uploads to signed S3/R2 URLs before large beta.
4. Replace temporary score generation with asynchronous AI analysis.
5. Add scheduled ranking recalculation.
6. Add materialized feed/ranking cache using Redis.
7. Add billing provider adapters only after entitlement rules are stable.
