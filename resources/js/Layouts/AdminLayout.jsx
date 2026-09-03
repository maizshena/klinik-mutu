import { useEffect, useState } from "react";
import { usePage } from "@inertiajs/react";
import { CheckCircle2, XCircle } from "lucide-react";
import Sidebar from "./Partials/Sidebar";
import Topbar from "./Partials/Topbar";

const COLLAPSE_STORAGE_KEY = "klinik-mutu-sidebar-collapsed";

export default function AdminLayout({ children, title }) {
  const [mobileOpen, setMobileOpen] = useState(false);
  const [collapsed, setCollapsed] = useState(false);
  const { flash } = usePage().props;

  // restore the user's last collapse preference on mount
  useEffect(() => {
    const stored = window.localStorage.getItem(COLLAPSE_STORAGE_KEY);
    if (stored === "true") setCollapsed(true);
  }, []);

  function toggleCollapse() {
    setCollapsed((prev) => {
      const next = !prev;
      window.localStorage.setItem(COLLAPSE_STORAGE_KEY, String(next));
      return next;
    });
  }

  return (
    <div className="min-h-screen bg-brand-bg">
      <Sidebar
        mobileOpen={mobileOpen}
        onClose={() => setMobileOpen(false)}
        collapsed={collapsed}
        onToggleCollapse={toggleCollapse}
      />

      <div className={`transition-all duration-200 ${collapsed ? "lg:pl-16" : "lg:pl-60"}`}>
        <Topbar onMenuClick={() => setMobileOpen(true)} title={title} />

        <main className="p-4 sm:p-5 max-w-[1400px] mx-auto">
          {flash?.success && (
            <div className="mb-3 flex items-center gap-2 rounded-2xl bg-emerald-50 px-4 py-2.5 text-sm text-emerald-800">
              <CheckCircle2 className="h-4 w-4 flex-shrink-0" />
              {flash.success}
            </div>
          )}
          {flash?.error && (
            <div className="mb-3 flex items-center gap-2 rounded-2xl bg-red-50 px-4 py-2.5 text-sm text-red-800">
              <XCircle className="h-4 w-4 flex-shrink-0" />
              {flash.error}
            </div>
          )}

          {children}
        </main>
      </div>
    </div>
  );
}