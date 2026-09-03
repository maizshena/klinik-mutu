import { AlertTriangle } from "lucide-react";

function urgencyTone(days) {
  if (days >= 5) return "bg-red-50 text-red-600";
  if (days >= 2) return "bg-amber-50 text-amber-600";
  return "bg-slate-100 text-slate-500";
}

export default function AlertList({ items }) {
  if (!items?.length) {
    return (
      <div className="flex flex-col items-center justify-center py-8 text-center">
        <div className="h-9 w-9 rounded-full bg-emerald-50 flex items-center justify-center mb-2">
          <AlertTriangle className="h-4 w-4 text-emerald-500" />
        </div>
        <p className="text-xs text-slate-400">Tidak ada permohonan yang menunggu lama</p>
      </div>
    );
  }

  return (
    <div className="space-y-1">
      {items.map((item) => (
        <div key={item.id} className="flex items-center justify-between gap-3 py-2">
          <div className="min-w-0">
            <p className="text-sm font-medium text-brand-dark truncate">{item.title}</p>
            <p className="text-xs text-slate-400 truncate">{item.subtitle} · {item.waitingSince}</p>
          </div>
          <span className={`flex-shrink-0 rounded-full px-2 py-0.5 text-xs font-bold ${urgencyTone(item.daysWaiting)}`}>
            {item.daysWaiting}h
          </span>
        </div>
      ))}
    </div>
  );
}