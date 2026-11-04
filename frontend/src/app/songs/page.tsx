"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import { Mic2, Music2 } from "lucide-react";
import { AppShell } from "@/components/AppShell";
import { Protected } from "@/components/Protected";
import { apiFetch, Song, unwrapList } from "@/lib/api";

export default function SongsPage() {
  const [songs, setSongs] = useState<Song[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    apiFetch<unknown>("/songs?per_page=50")
      .then((data) => setSongs(unwrapList<Song>(data)))
      .finally(() => setLoading(false));
  }, []);

  return (
    <Protected>
      <AppShell>
        <div className="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <h1 className="text-3xl font-bold">Músicas disponíveis</h1>
            <p className="mt-2 text-sm text-zinc-400">Escolha uma base, prepare sua voz e publique sua primeira performance.</p>
          </div>
          <Link href="/feed" className="inline-flex h-11 items-center justify-center gap-2 rounded-md border border-cyan-300/30 px-4 text-sm font-semibold text-cyan-100 hover:bg-cyan-300/10">
            <Music2 className="size-4" />
            Ver feed
          </Link>
        </div>

        {loading ? (
          <p className="mt-8 text-sm text-zinc-400">Carregando catálogo...</p>
        ) : (
          <div className="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {songs.map((song) => (
              <article key={song.id} className="overflow-hidden rounded-lg border border-white/10 bg-zinc-950">
                <div className="aspect-[16/10] bg-zinc-900">
                  {song.cover_image_url ? (
                    <div className="h-full w-full bg-cover bg-center" style={{ backgroundImage: `url(${song.cover_image_url})` }} />
                  ) : (
                    <div className="grid h-full place-items-center bg-violet-950 text-cyan-200">
                      <Music2 className="size-10" />
                    </div>
                  )}
                </div>
                <div className="p-4">
                  <div className="flex items-start justify-between gap-3">
                    <div>
                      <h2 className="font-semibold">{song.title}</h2>
                      <p className="mt-1 text-sm text-zinc-400">{song.artist}</p>
                    </div>
                    <span className="rounded-md bg-cyan-300/10 px-2 py-1 text-xs font-semibold text-cyan-100">Nv. {song.difficulty_level}</span>
                  </div>
                  <p className="mt-3 text-sm text-zinc-500">{[song.genre, song.language, song.bpm ? `${song.bpm} BPM` : null].filter(Boolean).join(" • ")}</p>
                  <Link href={`/songs/${song.id}`} className="mt-4 inline-flex h-10 w-full items-center justify-center gap-2 rounded-md bg-violet-500 text-sm font-semibold text-white hover:bg-violet-400">
                    <Mic2 className="size-4" />
                    Cantar agora
                  </Link>
                </div>
              </article>
            ))}
          </div>
        )}
      </AppShell>
    </Protected>
  );
}
