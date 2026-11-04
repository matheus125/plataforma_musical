"use client";

import { FormEvent, useEffect, useState } from "react";
import Link from "next/link";
import { useParams, useRouter } from "next/navigation";
import { ArrowLeft, FileAudio, Send } from "lucide-react";
import { AppShell } from "@/components/AppShell";
import { Protected } from "@/components/Protected";
import { apiFetch, Performance, Song } from "@/lib/api";

export default function PublishPerformancePage() {
  const params = useParams<{ id: string }>();
  const router = useRouter();
  const [song, setSong] = useState<Song | null>(null);
  const [file, setFile] = useState<File | null>(null);
  const [message, setMessage] = useState("");
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    apiFetch<Song>(`/songs/${params.id}`).then(setSong).catch(() => setSong(null));
  }, [params.id]);

  async function handleSubmit(event: FormEvent) {
    event.preventDefault();
    if (!file) {
      setMessage("Selecione um arquivo de áudio para publicar.");
      return;
    }

    const form = new FormData();
    form.append("song_id", params.id);
    form.append("audio", file);
    form.append("is_public", "1");

    setLoading(true);
    setMessage("");

    try {
      const performance = await apiFetch<Performance>("/performances", {
        method: "POST",
        body: form,
      });
      setMessage("Performance publicada com sucesso.");
      router.push(`/performances/${performance.id}`);
    } catch (error) {
      setMessage(error instanceof Error ? error.message : "Nao foi possivel publicar a performance.");
    } finally {
      setLoading(false);
    }
  }

  return (
    <Protected>
      <AppShell>
        <Link href={`/songs/${params.id}`} className="inline-flex items-center gap-2 text-sm text-cyan-100 hover:text-cyan-200">
          <ArrowLeft className="size-4" />
          Voltar para música
        </Link>

        <form onSubmit={handleSubmit} className="mt-6 mx-auto max-w-2xl rounded-lg border border-white/10 bg-zinc-950 p-6">
          <h1 className="text-2xl font-bold">Publicar performance</h1>
          <p className="mt-2 text-sm text-zinc-400">{song ? `${song.title} • ${song.artist}` : "Carregando música..."}</p>

          <label className="mt-6 flex min-h-40 cursor-pointer flex-col items-center justify-center rounded-lg border border-dashed border-cyan-300/40 bg-cyan-300/5 p-5 text-center hover:bg-cyan-300/10">
            <FileAudio className="size-10 text-cyan-200" />
            <span className="mt-3 text-sm font-semibold">{file ? file.name : "Selecionar áudio"}</span>
            <span className="mt-1 text-xs text-zinc-500">MP3, WAV, WEBM ou M4A até 20MB</span>
            <input className="sr-only" type="file" accept=".mp3,.wav,.webm,.m4a,audio/mpeg,audio/wav,audio/webm,audio/mp4" onChange={(event) => setFile(event.target.files?.[0] ?? null)} />
          </label>

          {message && <p className="mt-4 rounded-md border border-violet-300/30 bg-violet-400/10 p-3 text-sm text-violet-100">{message}</p>}

          <button disabled={loading} className="mt-6 inline-flex h-11 w-full items-center justify-center gap-2 rounded-md bg-cyan-300 font-semibold text-zinc-950 disabled:cursor-not-allowed disabled:opacity-60">
            <Send className="size-5" />
            {loading ? "Publicando..." : "Publicar performance"}
          </button>
        </form>
      </AppShell>
    </Protected>
  );
}
