"use client";

import { useEffect, useState } from "react";
import type { ReactNode } from "react";
import Link from "next/link";
import { Headphones, Send, Sparkles } from "lucide-react";
import { AppShell } from "@/components/AppShell";
import { Protected } from "@/components/Protected";
import { apiFetch, Performance, Song, unwrapList } from "@/lib/api";

export default function DashboardPage() {
  const [songs, setSongs] = useState<Song[]>([]);
  const [performances, setPerformances] = useState<Performance[]>([]);

  useEffect(() => {
    apiFetch<unknown>("/songs?per_page=5").then((data) => setSongs(unwrapList<Song>(data))).catch(() => null);
    apiFetch<unknown>("/performances?per_page=5").then((data) => setPerformances(unwrapList<Performance>(data))).catch(() => null);
  }, []);

  return (
    <Protected>
      <AppShell>
        <div className="grid gap-5 md:grid-cols-3">
          <SummaryCard icon={<Headphones className="size-5" />} label="Musicas disponiveis" value={songs.length} />
          <SummaryCard icon={<Send className="size-5" />} label="Envios recentes" value={performances.length} />
          <SummaryCard icon={<Sparkles className="size-5" />} label="Status do MVP" value="Base" />
        </div>
        <section className="mt-8 grid gap-5 lg:grid-cols-2">
          <div className="rounded-lg border border-white/10 bg-zinc-950 p-5">
            <h2 className="font-semibold">Musicas para praticar</h2>
            <div className="mt-4 space-y-3">{songs.map((song) => <Row key={song.id} title={song.title} subtitle={`${song.artist} - nível ${song.difficulty_level}`} />)}</div>
            <Link className="mt-5 inline-flex h-10 items-center rounded-md bg-cyan-300 px-4 text-sm font-semibold text-zinc-950" href="/songs">Ver catálogo</Link>
          </div>
          <div className="rounded-lg border border-white/10 bg-zinc-950 p-5">
            <h2 className="font-semibold">Ultimas performances</h2>
            <div className="mt-4 space-y-3">{performances.length ? performances.map((item) => <Row key={item.id} title={item.song?.title ?? "Performance"} subtitle={`Score ${item.score_overall ?? "-"}`} />) : <p className="text-sm text-zinc-400">Nenhum envio ainda.</p>}</div>
            <Link className="mt-5 inline-flex h-10 items-center rounded-md bg-violet-500 px-4 text-sm font-semibold text-white" href="/feed">Ver feed</Link>
          </div>
        </section>
      </AppShell>
    </Protected>
  );
}

function SummaryCard({ icon, label, value }: { icon: ReactNode; label: string; value: string | number }) {
  return <div className="rounded-lg border border-white/10 bg-zinc-950 p-5"><div className="text-cyan-200">{icon}</div><p className="mt-4 text-sm text-zinc-400">{label}</p><strong className="mt-1 block text-3xl">{value}</strong></div>;
}

function Row({ title, subtitle }: { title: string; subtitle: string }) {
  return <div className="rounded-md border border-white/10 bg-white/[0.03] p-3"><p className="font-medium">{title}</p><p className="mt-1 text-sm text-zinc-400">{subtitle}</p></div>;
}
