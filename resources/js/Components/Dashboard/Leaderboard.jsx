import { Trophy } from "lucide-react";

const RANK_STYLES = [
  "bg-amber-100 text-amber-700",
  "bg-slate-100 text-slate-600",
  "bg-orange-100 text-orange-700",
];

export default function Leaderboard({ items }) {
  if (!items?.length) {
    return (
      <div className="flex flex-col items-center justify-center py-8 text-center">
        <Trophy className="h-5 w-5 text-brand-text/30 mb-2" />
        <p className="text-xs text-brand-text/40">Belum ada data peringkat</p>
      </div>
    );
  }

  return (
    <div className="space-y-1">
      {items.map((item, index) => (
        <div key={item.id} className="flex items-center gap-3 py-1.5">
          <span
            className={`h-6 w-6 flex-shrink-0 rounded-lg flex items-center justify-center text-xs font-bold
              ${RANK_STYLES[index] ?? "bg-slate-50 text-brand-text/50"}`}
          >
            {index + 1}
          </span>
          <p className="text-sm font-medium text-brand-dark truncate flex-1">{item.name}</p>
          <p className="text-sm font-bold text-brand-primary flex-shrink-0">{item.completed}</p>
        </div>
      ))}
    </div>
  );
}