"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import { useParams } from "next/navigation";
import { ArrowLeft, Mic2, Music2, Upload } from "lucide-react";
import { AppShell } from "@/components/AppShell";
import { Protected } from "@/components/Protected";
import { apiFetch, Song } from "@/lib/api";

export default function SongDetailPage() {
  const params = useParams<{ id: string }>();
  const [song, setSong] = useState<Song | null>(null);

  useEffect(() => {
    apiFetch<Song>(`/songs/${params.id}`).then(setSong).catch(() => setSong(null));
  }, [params.id]);

  return (
    <Protected>
      <AppShell>
        <Link href="/songs" className="inline-flex items-center gap-2 text-sm text-cyan-100 hover:text-cyan-200">
          <ArrowLeft className="size-4" />
          Voltar ao catálogo
        </Link>

        {song ? (
          <div className="mt-6 grid gap-6 lg:grid-cols-[420px_1fr]">
            <div className="overflow-hidden rounded-lg border border-white/10 bg-zinc-950">
              <div className="aspect-square bg-zinc-900">
                {song.cover_image_url ? (
                  <div className="h-full w-full bg-cover bg-center" style={{ backgroundImage: `url(${song.cover_image_url})` }} />
                ) : (
                  <div className="grid h-full place-items-center bg-violet-950 text-cyan-200">
                    <Music2 className="size-14" />
                  </div>
                )}
              </div>
            </div>

            <section className="rounded-lg border border-white/10 bg-zinc-950 p-6">
              <p className="text-sm font-semibold text-cyan-200">{song.genre ?? "Performance"}</p>
              <h1 className="mt-2 text-3xl font-bold">{song.title}</h1>
              <p className="mt-2 text-zinc-400">{song.artist}</p>
              <div className="mt-6 grid gap-3 sm:grid-cols-4">
                <Metric label="Nível" value={song.difficulty_level} />
                <Metric label="Tom" value={song.key ?? "-"} />
                <Metric label="BPM" value={song.bpm ?? "-"} />
                <Metric label="Idioma" value={song.language ?? "-"} />
              </div>

              {song.audio_url && (
                <div className="mt-6">
                  <p className="mb-2 text-sm text-zinc-400">Base da música</p>
                  <audio controls className="w-full" src={song.audio_url} />
                </div>
              )}

              <div className="mt-6 flex flex-col gap-3 sm:flex-row">
                <Link href={`/songs/${song.id}/perform`} className="inline-flex h-11 items-center justify-center gap-2 rounded-md bg-cyan-300 px-5 font-semibold text-zinc-950 shadow-[0_0_28px_rgba(34,211,238,.26)]">
                  <Upload className="size-5" />
                  Enviar performance
                </Link>
                <button disabled className="inline-flex h-11 items-center justify-center gap-2 rounded-md border border-violet-300/30 px-5 font-semibold text-violet-100 opacity-60">
                  <Mic2 className="size-5" />
                  Gravar áudio
                </button>
              </div>
            </section>
          </div>
        ) : (
          <p className="mt-8 text-sm text-zinc-400">Carregando música...</p>
        )}
      </AppShell>
    </Protected>
  );
}

function Metric({ label, value }: { label: string; value: string | number }) {
  return (
    <div className="rounded-md border border-white/10 bg-white/[0.03] p-3">
      <p className="text-xs text-zinc-500">{label}</p>
      <strong className="mt-1 block text-lg">{value}</strong>
    </div>
  );
}
