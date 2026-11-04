"use client";

import { useEffect } from "react";
import type { ReactNode } from "react";
import { useRouter } from "next/navigation";
import { useAuth } from "@/components/AuthProvider";

export function Protected({ children }: { children: ReactNode }) {
  const { loading, user } = useAuth();
  const router = useRouter();

  useEffect(() => {
    if (!loading && !user) router.replace("/login");
  }, [loading, router, user]);

  if (loading || !user) {
    return <div className="grid min-h-screen place-items-center bg-zinc-950 text-cyan-200">Carregando...</div>;
  }

  return children;
}
