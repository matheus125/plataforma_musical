"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import { useParams } from "next/navigation";
import { ArrowLeft, Gauge, Music2 } from "lucide-react";
import { AppShell } from "@/components/AppShell";
import { Protected } from "@/components/Protected";
import { apiFetch, Performance } from "@/lib/api";

export default function PerformanceDetailPage() {
  const params = useParams<{ id: string }>();
  const [performance, setPerformance] = useState<Performance | null>(null);

  useEffect(() => {
    apiFetch<Performance>(`/performances/${params.id}`).then(setPerformance).catch(() => setPerformance(null));
  }, [params.id]);

  return (
    <Protected>
      <AppShell>
        <Link href="/feed" className="inline-flex items-center gap-2 text-sm text-cyan-100 hover:text-cyan-200">
          <ArrowLeft className="size-4" />
          Voltar ao feed
        </Link>

        {performance ? (
          <section className="mt-6 rounded-lg border border-white/10 bg-zinc-950 p-6">
            <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
              <div>
                <p className="text-sm text-cyan-200">{performance.user?.name ?? "Participante"}</p>
                <h1 className="mt-2 text-3xl font-bold">{performance.song?.title ?? "Performance"}</h1>
                <p className="mt-2 text-zinc-400">{performance.song?.artist}</p>
              </div>
              <div className="inline-flex items-center gap-2 rounded-md bg-violet-500 px-4 py-3 font-bold text-white">
                <Gauge className="size-5" />
                {performance.score_overall ?? "-"}
              </div>
            </div>

            {performance.audio_url && <audio controls className="mt-6 w-full" src={performance.audio_url} />}

            <div className="mt-6 grid gap-3 sm:grid-cols-3">
              <Score label="Score geral" value={performance.score_overall} />
              <Score label="Afinação" value={performance.score_pitch} />
              <Score label="Ritmo" value={performance.score_rhythm} />
            </div>

            <p className="mt-6 text-sm text-zinc-500">Publicado em {formatDate(performance.created_at)}</p>
          </section>
        ) : (
          <div className="mt-8 rounded-lg border border-white/10 bg-zinc-950 p-6 text-zinc-400">
            <Music2 className="mb-3 size-6 text-cyan-200" />
            Carregando performance...
          </div>
        )}
      </AppShell>
    </Protected>
  );
}

function Score({ label, value }: { label: string; value?: number | null }) {
  return (
    <div className="rounded-md border border-white/10 bg-white/[0.03] p-4">
      <p className="text-sm text-zinc-400">{label}</p>
      <strong className="mt-2 block text-3xl text-cyan-100">{value ?? "-"}</strong>
    </div>
  );
}

function formatDate(value?: string | null) {
  if (!value) return "-";
  return new Intl.DateTimeFormat("pt-BR", { dateStyle: "medium", timeStyle: "short" }).format(new Date(value));
}
