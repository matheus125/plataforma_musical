"use client";

import Link from "next/link";
import { FormEvent, useState } from "react";
import { LogIn } from "lucide-react";
import { useAuth } from "@/components/AuthProvider";

export default function LoginPage() {
  const { login } = useAuth();
  const [email, setEmail] = useState("test@example.com");
  const [password, setPassword] = useState("password");
  const [error, setError] = useState("");
  const [busy, setBusy] = useState(false);

  async function handleSubmit(event: FormEvent) {
    event.preventDefault();
    setBusy(true);
    setError("");
    try {
      await login(email, password);
    } catch (err) {
      setError(err instanceof Error ? err.message : "Erro ao entrar.");
    } finally {
      setBusy(false);
    }
  }

  return (
    <main className="grid min-h-screen place-items-center bg-[radial-gradient(circle_at_top,#7c3aed33,transparent_36%),#050508] px-5 text-white">
      <form onSubmit={handleSubmit} className="w-full max-w-md rounded-lg border border-white/10 bg-zinc-950/85 p-6 shadow-2xl">
        <h1 className="text-2xl font-bold">Entrar na arena</h1>
        <p className="mt-2 text-sm text-zinc-400">Acesse o painel do MVP.</p>
        <label className="mt-6 block text-sm text-zinc-300">
          Email
          <input className="mt-2 h-11 w-full rounded-md border border-white/10 bg-black px-3 text-white outline-none focus:border-cyan-300" value={email} onChange={(event) => setEmail(event.target.value)} type="email" required />
        </label>
        <label className="mt-4 block text-sm text-zinc-300">
          Senha
          <input className="mt-2 h-11 w-full rounded-md border border-white/10 bg-black px-3 text-white outline-none focus:border-cyan-300" value={password} onChange={(event) => setPassword(event.target.value)} type="password" required />
        </label>
        {error && <p className="mt-4 rounded-md border border-red-400/30 bg-red-500/10 p-3 text-sm text-red-200">{error}</p>}
        <button className="mt-6 inline-flex h-11 w-full items-center justify-center gap-2 rounded-md bg-cyan-300 font-semibold text-zinc-950 disabled:opacity-60" disabled={busy}>
          <LogIn className="size-5" />
          {busy ? "Entrando..." : "Entrar"}
        </button>
        <Link className="mt-4 block text-center text-sm text-violet-200 hover:text-violet-100" href="/cadastro">Criar cadastro</Link>
      </form>
    </main>
  );
}
