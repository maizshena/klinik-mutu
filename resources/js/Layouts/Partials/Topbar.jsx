import { Menu, Search } from "lucide-react";

// stripped down to just: mobile menu trigger, page title/breadcrumb, and search —
// no notification bell or avatar dropdown, those responsibilities moved to the sidebar
export default function Topbar({ onMenuClick, title }) {
  return (
    <header className="sticky top-0 z-30 h-14 bg-brand-bg/80 backdrop-blur">
      <div className="h-full px-4 sm:px-6 flex items-center justify-between gap-4">
        <div className="flex items-center gap-3 min-w-0">
          <button
            type="button"
            onClick={onMenuClick}
            className="lg:hidden focus-ring rounded-lg p-1.5 text-brand-text/50 hover:bg-white flex-shrink-0"
          >
            <Menu className="h-5 w-5" />
          </button>
          {title && <h1 className="text-sm font-semibold text-brand-dark truncate">{title}</h1>}
        </div>

        <div className="flex items-center flex-1 max-w-xs">
          <div className="relative w-full">
            <Search className="h-4 w-4 text-brand-text/30 absolute left-3 top-1/2 -translate-y-1/2" />
            <input
              type="text"
              placeholder="Cari..."
              className="focus-ring w-full rounded-lg border-0 bg-white pl-9 pr-3 py-1.5 text-sm placeholder:text-brand-text/40 shadow-xs"
            />
          </div>
        </div>
      </div>
    </header>
  );
}