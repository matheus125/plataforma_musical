"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import { Radio, Trophy } from "lucide-react";
import { AppShell } from "@/components/AppShell";
import { Protected } from "@/components/Protected";
import { apiFetch, Performance, unwrapList } from "@/lib/api";

export default function FeedPage() {
  const [performances, setPerformances] = useState<Performance[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    apiFetch<unknown>("/performances?per_page=30")
      .then((data) => setPerformances(unwrapList<Performance>(data)))
      .finally(() => setLoading(false));
  }, []);

  return (
    <Protected>
      <AppShell>
        <div className="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <h1 className="text-3xl font-bold">Feed competitivo</h1>
            <p className="mt-2 text-sm text-zinc-400">Performances públicas recém-publicadas pela comunidade.</p>
          </div>
          <Link href="/songs" className="inline-flex h-11 items-center justify-center gap-2 rounded-md bg-cyan-300 px-4 text-sm font-semibold text-zinc-950">
            <Radio className="size-4" />
            Cantar agora
          </Link>
        </div>

        {loading ? (
          <p className="mt-8 text-sm text-zinc-400">Carregando feed...</p>
        ) : (
          <div className="mt-6 space-y-4">
            {performances.length ? performances.map((performance) => (
              <article key={performance.id} className="rounded-lg border border-white/10 bg-zinc-950 p-5">
                <div className="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                  <div>
                    <p className="text-sm text-cyan-200">{performance.user?.name ?? "Participante"}</p>
                    <h2 className="mt-1 text-xl font-semibold">{performance.song?.title ?? "Performance"}</h2>
                    <p className="mt-1 text-sm text-zinc-400">{performance.song?.artist} • {formatDate(performance.created_at)}</p>
                  </div>
                  <Link href={`/performances/${performance.id}`} className="inline-flex h-10 items-center justify-center gap-2 rounded-md border border-violet-300/30 px-4 text-sm font-semibold text-violet-100 hover:bg-violet-400/10">
                    <Trophy className="size-4" />
                    Ver detalhes
                  </Link>
                </div>
                {performance.audio_url && <audio controls className="mt-4 w-full" src={performance.audio_url} />}
                <div className="mt-4 grid gap-3 sm:grid-cols-3">
                  <MiniScore label="Geral" value={performance.score_overall} />
                  <MiniScore label="Afinação" value={performance.score_pitch} />
                  <MiniScore label="Ritmo" value={performance.score_rhythm} />
                </div>
              </article>
            )) : <p className="rounded-lg border border-white/10 bg-zinc-950 p-6 text-sm text-zinc-400">Nenhuma performance pública ainda.</p>}
          </div>
        )}
      </AppShell>
    </Protected>
  );
}

function MiniScore({ label, value }: { label: string; value?: number | null }) {
  return (
    <div className="rounded-md bg-white/[0.04] p-3">
      <p className="text-xs text-zinc-500">{label}</p>
      <strong className="mt-1 block text-xl text-cyan-100">{value ?? "-"}</strong>
    </div>
  );
}

function formatDate(value?: string | null) {
  if (!value) return "-";
  return new Intl.DateTimeFormat("pt-BR", { dateStyle: "medium" }).format(new Date(value));
}
