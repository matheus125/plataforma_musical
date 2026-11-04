import Link from "next/link";
import { Mic2, Music2, Trophy } from "lucide-react";

export default function Home() {
  return (
    <main className="min-h-screen bg-[radial-gradient(circle_at_top_left,#6d28d930,transparent_30%),radial-gradient(circle_at_bottom_right,#06b6d430,transparent_34%),#050508] text-white">
      <section className="mx-auto flex min-h-screen w-full max-w-6xl flex-col justify-center px-5 py-12">
        <div className="mb-10 flex items-center gap-3 text-cyan-200">
          <div className="grid size-11 place-items-center rounded-lg border border-cyan-300/30 bg-cyan-300/10">
            <Mic2 className="size-6" />
          </div>
          <span className="text-sm font-semibold uppercase tracking-[0.22em]">MVP competitivo</span>
        </div>
        <div className="max-w-3xl">
          <h1 className="text-4xl font-bold leading-tight sm:text-6xl">
            Plataforma de Performance Musical Competitiva
          </h1>
          <p className="mt-6 max-w-2xl text-base leading-8 text-zinc-300 sm:text-lg">
            Base limpa para cadastro de atletas musicais, catalogo de musicas e envio de performances para avaliacao futura.
          </p>
        </div>
        <div className="mt-8 flex flex-col gap-3 sm:flex-row">
          <Link className="inline-flex h-12 items-center justify-center gap-2 rounded-md bg-cyan-300 px-5 font-semibold text-zinc-950 shadow-[0_0_30px_rgba(34,211,238,.35)]" href="/login">
            <Music2 className="size-5" />
            Entrar
          </Link>
          <Link className="inline-flex h-12 items-center justify-center gap-2 rounded-md border border-violet-300/40 px-5 font-semibold text-violet-100 hover:bg-violet-400/10" href="/cadastro">
            <Trophy className="size-5" />
            Criar conta
          </Link>
        </div>
      </section>
    </main>
  );
}
