export type User = {
  id: number;
  name: string;
  email: string;
};

export type Song = {
  id: number;
  title: string;
  artist: string;
  genre?: string | null;
  language?: string | null;
  key?: string | null;
  bpm?: number | null;
  difficulty_level: number;
  audio_url?: string | null;
  cover_image_url?: string | null;
  is_active?: boolean;
  created_at?: string | null;
};

export type Performance = {
  id: number;
  audio_url?: string | null;
  duration_seconds?: number | null;
  score_overall?: number | null;
  score_pitch?: number | null;
  score_rhythm?: number | null;
  likes_count?: number;
  views_count?: number;
  is_public?: boolean;
  song?: Song;
  user?: User;
  created_at?: string | null;
};

type ApiEnvelope<T> = {
  success: boolean;
  message: string;
  data: T;
  errors?: Record<string, string[]>;
};

export const API_URL = process.env.NEXT_PUBLIC_API_URL ?? "http://localhost:8000/api";

export async function apiFetch<T>(path: string, options: RequestInit = {}) {
  const token = typeof window !== "undefined" ? localStorage.getItem("auth_token") : null;
  const isFormData = options.body instanceof FormData;
  const response = await fetch(`${API_URL}${path}`, {
    ...options,
    headers: {
      Accept: "application/json",
      ...(isFormData ? {} : { "Content-Type": "application/json" }),
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...options.headers,
    },
  });

  const payload = (await response.json().catch(() => null)) as ApiEnvelope<T> | null;

  if (!response.ok || !payload?.success) {
    const message = payload?.message ?? "Nao foi possivel concluir a solicitacao.";
    throw new Error(message);
  }

  return payload.data;
}

export function unwrapList<T>(payload: unknown): T[] {
  if (Array.isArray(payload)) return payload as T[];
  if (payload && typeof payload === "object" && "data" in payload) {
    const data = (payload as { data: unknown }).data;
    return Array.isArray(data) ? (data as T[]) : [];
  }
  return [];
}
