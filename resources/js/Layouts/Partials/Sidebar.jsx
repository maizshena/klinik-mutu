import { Link, router, usePage } from "@inertiajs/react";
import {
  LayoutDashboard,
  FileText,
  MessageSquareText,
  ClipboardCheck,
  ListChecks,
  ShieldCheck,
  X,
  PanelLeftClose,
  PanelLeftOpen,
  LogOut,
} from "lucide-react";

const NAV_GROUPS = [
  {
    label: "Utama",
    items: [{ name: "Dashboard", href: "dashboard", icon: LayoutDashboard }],
  },
  {
    label: "Layanan",
    items: [
      { name: "Permohonan", href: "permohonan.index", icon: FileText },
      { name: "Konsultasi Teknis", href: "konsultasi-teknis.index", icon: MessageSquareText },
      { name: "Pembinaan Proaktif", href: "pembinaan-proaktif.index", icon: ClipboardCheck },
      { name: "Tindak Lanjut", href: "followup.index", icon: ListChecks },
    ],
  },
  {
    label: "Administrasi",
    items: [{ name: "Klaim KUSUKA", href: "kusuka.claims.index", icon: ShieldCheck }],
  },
];

function NavLink({ item, currentUrl, collapsed }) {
  const Icon = item.icon;
  const href = route(item.href);
  const isActive = currentUrl.startsWith(href);

  return (
    <Link
      href={href}
      title={collapsed ? item.name : undefined}
      className={`focus-ring flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium transition-colors
        ${collapsed ? "justify-center px-2" : ""}
        ${isActive ? "bg-brand-secondary/10 text-brand-primary" : "text-brand-text/70 hover:bg-brand-bg hover:text-brand-dark"}`}
    >
      <Icon className="h-4 w-4 flex-shrink-0" />
      {!collapsed && item.name}
    </Link>
  );
}

export default function Sidebar({ mobileOpen, onClose, collapsed, onToggleCollapse }) {
  const { url, auth } = usePage().props ? usePage() : { props: {} };
  const page = usePage();
  const currentUrl = page.url;
  const user = page.props.auth?.user;

  function logout() {
    router.post(route("logout"));
  }

  const content = (
    <div className="flex h-full flex-col">
      {/* brand header */}
      <div className={`flex items-center h-14 border-b border-slate-100 ${collapsed ? "justify-center px-2" : "justify-between px-4"}`}>
        <Link href={route("dashboard")} className="flex items-center gap-2 min-w-0">
          <div className="h-7 w-7 rounded-lg bg-brand-primary flex items-center justify-center flex-shrink-0">
            <ShieldCheck className="h-4 w-4 text-white" />
          </div>
          {!collapsed && <p className="text-sm font-semibold text-brand-dark truncate">Klinik Mutu</p>}
        </Link>

        <button
          type="button"
          onClick={onClose}
          className="lg:hidden focus-ring rounded-lg p-1.5 text-brand-text/50 hover:bg-brand-bg flex-shrink-0"
        >
          <X className="h-4.5 w-4.5" />
        </button>
      </div>

      {/* nav */}
      <nav className="flex-1 overflow-y-auto px-2.5 py-3 space-y-5">
        {NAV_GROUPS.map((group) => (
          <div key={group.label}>
            {!collapsed && (
              <p className="px-3 text-[10px] font-semibold uppercase tracking-wider text-brand-text/35 mb-1.5">
                {group.label}
              </p>
            )}
            <div className="space-y-0.5">
              {group.items.map((item) => (
                <NavLink key={item.name} item={item} currentUrl={currentUrl} collapsed={collapsed} />
              ))}
            </div>
          </div>
        ))}
      </nav>

      {/* collapse toggle, desktop only */}
      <div className="hidden lg:block px-2.5 pb-2">
        <button
          type="button"
          onClick={onToggleCollapse}
          className={`focus-ring flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-medium text-brand-text/50 hover:bg-brand-bg hover:text-brand-dark w-full transition-colors
            ${collapsed ? "justify-center px-2" : ""}`}
        >
          {collapsed ? <PanelLeftOpen className="h-4 w-4" /> : <PanelLeftClose className="h-4 w-4" />}
          {!collapsed && "Ciutkan"}
        </button>
      </div>

      {/* user profile block, moved from topbar per new layout spec */}
      <div className={`border-t border-slate-100 p-3 ${collapsed ? "flex justify-center" : ""}`}>
        {collapsed ? (
          <button
            type="button"
            onClick={logout}
            title="Keluar"
            className="focus-ring h-9 w-9 rounded-full bg-brand-secondary/10 flex items-center justify-center text-brand-primary text-xs font-bold hover:bg-brand-secondary/20"
          >
            {user?.nama_lengkap?.charAt(0) ?? "?"}
          </button>
        ) : (
          <div className="flex items-center gap-2.5">
            <div className="h-9 w-9 rounded-full bg-brand-secondary/10 flex items-center justify-center text-brand-primary text-xs font-bold flex-shrink-0">
              {user?.nama_lengkap?.charAt(0) ?? "?"}
            </div>
            <div className="min-w-0 flex-1">
              <p className="text-xs font-semibold text-brand-dark truncate">{user?.nama_lengkap}</p>
              <p className="text-[11px] text-brand-text/40 truncate capitalize">{user?.role?.replace(/_/g, " ")}</p>
            </div>
            <button
              type="button"
              onClick={logout}
              title="Keluar"
              className="focus-ring flex-shrink-0 rounded-lg p-1.5 text-brand-text/40 hover:bg-brand-bg hover:text-red-600"
            >
              <LogOut className="h-4 w-4" />
            </button>
          </div>
        )}
      </div>
    </div>
  );

  return (
    <>
      <aside
        className={`hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 bg-white transition-all duration-200
          ${collapsed ? "lg:w-16" : "lg:w-60"}`}
      >
        {content}
      </aside>

      {mobileOpen && (
        <div className="lg:hidden fixed inset-0 z-40">
          <div className="absolute inset-0 bg-brand-dark/40" onClick={onClose} />
          <aside className="absolute inset-y-0 left-0 w-60 bg-white">{content}</aside>
        </div>
      )}
    </>
  );
}