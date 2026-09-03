import { FileText, ClipboardCheck } from "lucide-react";

const TYPE_ICON = { permohonan: FileText, pembinaan: ClipboardCheck };
const TYPE_TONE = { permohonan: "bg-brand-secondary/10 text-brand-primary", pembinaan: "bg-emerald-50 text-emerald-600" };

// horizontal strip of mini activity cards, matches the reference's "recent activity" row
export default function ActivityStrip({ items }) {
  if (!items?.length) {
    return <p className="text-xs text-slate-400 py-6 text-center">Belum ada aktivitas terbaru</p>;
  }

  return (
    <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
      {items.map((item) => {
        const Icon = TYPE_ICON[item.type] ?? FileText;

        return (
          <div key={`${item.type}-${item.id}`} className="rounded-xl bg-brand-bg p-3">
            <div className={`h-7 w-7 rounded-lg flex items-center justify-center mb-2 ${TYPE_TONE[item.type]}`}>
              <Icon className="h-3.5 w-3.5" />
            </div>
            <p className="text-xs font-semibold text-brand-dark truncate">{item.title}</p>
            <p className="text-[11px] text-slate-400 truncate mt-0.5">{item.subtitle}</p>
            <p className="text-[10px] text-slate-300 mt-1.5">{item.date}</p>
          </div>
        );
      })}
    </div>
  );
}