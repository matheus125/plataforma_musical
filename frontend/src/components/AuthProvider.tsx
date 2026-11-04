"use client";

import { createContext, useContext, useEffect, useMemo, useState } from "react";
import type { ReactNode } from "react";
import { useRouter } from "next/navigation";
import { apiFetch, User } from "@/lib/api";

type AuthContextValue = {
  user: User | null;
  loading: boolean;
  login: (email: string, password: string) => Promise<void>;
  register: (name: string, email: string, password: string) => Promise<void>;
  logout: () => Promise<void>;
};

const AuthContext = createContext<AuthContextValue | null>(null);

export function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);
  const router = useRouter();

  useEffect(() => {
    const token = localStorage.getItem("auth_token");
    if (!token) {
      queueMicrotask(() => setLoading(false));
      return;
    }

    apiFetch<User>("/me")
      .then(setUser)
      .catch(() => localStorage.removeItem("auth_token"))
      .finally(() => setLoading(false));
  }, []);

  const value = useMemo<AuthContextValue>(() => ({
    user,
    loading,
    async login(email, password) {
      const data = await apiFetch<{ user: User; token: string }>("/login", {
        method: "POST",
        body: JSON.stringify({ email, password }),
      });
      localStorage.setItem("auth_token", data.token);
      setUser(data.user);
      router.push("/dashboard");
    },
    async register(name, email, password) {
      const data = await apiFetch<{ user: User; token: string }>("/register", {
        method: "POST",
        body: JSON.stringify({ name, email, password, password_confirmation: password }),
      });
      localStorage.setItem("auth_token", data.token);
      setUser(data.user);
      router.push("/dashboard");
    },
    async logout() {
      await apiFetch("/logout", { method: "POST" }).catch(() => null);
      localStorage.removeItem("auth_token");
      setUser(null);
      router.push("/login");
    },
  }), [loading, router, user]);

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) throw new Error("useAuth precisa estar dentro de AuthProvider.");
  return context;
}
