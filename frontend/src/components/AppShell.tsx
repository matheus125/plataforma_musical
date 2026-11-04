"use client";

import Link from "next/link";
import type { ReactNode } from "react";
import { usePathname } from "next/navigation";
import { LogOut, Mic2, Music2, Radio, Gauge } from "lucide-react";
import { useAuth } from "@/components/AuthProvider";

const nav = [
  { href: "/dashboard", label: "Dashboard", icon: Gauge },
  { href: "/songs", label: "Músicas", icon: Music2 },
  { href: "/feed", label: "Feed", icon: Radio },
];

export function AppShell({ children }: { children: ReactNode }) {
  const pathname = usePathname();
  const { logout, user } = useAuth();

  return (
    <div className="min-h-screen bg-[#050508] text-zinc-50">
      <header className="border-b border-white/10 bg-zinc-950/85 backdrop-blur">
        <div className="mx-auto flex max-w-6xl flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
          <Link href="/dashboard" className="flex items-center gap-3">
            <span className="grid size-10 place-items-center rounded-lg bg-cyan-300 text-zinc-950 shadow-[0_0_24px_rgba(34,211,238,.32)]">
              <Mic2 className="size-5" />
            </span>
            <span className="font-semibold">Performance Musical</span>
          </Link>
          <nav className="flex flex-wrap items-center gap-2">
            {nav.map((item) => {
              const Icon = item.icon;
              const active = pathname === item.href;
              return (
                <Link key={item.href} href={item.href} className={`inline-flex h-10 items-center gap-2 rounded-md px-3 text-sm font-medium ${active ? "bg-violet-500 text-white" : "text-zinc-300 hover:bg-white/10"}`}>
                  <Icon className="size-4" />
                  {item.label}
                </Link>
              );
            })}
            <button onClick={logout} className="inline-flex h-10 items-center gap-2 rounded-md border border-white/10 px-3 text-sm text-zinc-300 hover:bg-white/10" title={`Sair de ${user?.name ?? "usuario"}`}>
              <LogOut className="size-4" />
              Sair
            </button>
          </nav>
        </div>
      </header>
      <main className="mx-auto w-full max-w-6xl px-5 py-8">{children}</main>
    </div>
  );
}
