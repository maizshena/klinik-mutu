import { Link } from "@inertiajs/react";
import { ShieldCheck } from "lucide-react";

// spacious, single-column layout for public-facing auth pages
// deliberately minimal chrome so the form stays the focus
export default function GuestLayout({ children }) {
  return (
    <div className="min-h-screen bg-brand-bg flex flex-col">
      <header className="border-b border-slate-200 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
          <Link href={route("home")} className="flex items-center gap-2.5">
            <div className="h-8 w-8 rounded-xl bg-brand-primary flex items-center justify-center">
              <ShieldCheck className="h-4.5 w-4.5 text-white" />
            </div>
            <div className="leading-tight">
              <p className="text-sm font-semibold text-brand-dark">Klinik Mutu</p>
              <p className="text-xs text-brand-text/60">Hasil Perikanan</p>
            </div>
          </Link>

          <Link href={route("home")} className="text-sm font-medium text-brand-text/60 hover:text-brand-dark transition-colors">
            Kembali ke Beranda
          </Link>
        </div>
      </header>

      <main className="flex-1 flex items-center justify-center px-4 py-14">
        <div className="w-full max-w-md">{children}</div>
      </main>

      <footer className="border-t border-slate-200 bg-white py-5">
        <p className="text-center text-xs text-brand-text/40">
          © {new Date().getFullYear()} Direktorat Pengolahan · Kementerian Kelautan dan Perikanan
        </p>
      </footer>
    </div>
  );
}