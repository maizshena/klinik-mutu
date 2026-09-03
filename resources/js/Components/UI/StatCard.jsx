// typography per new spec: label small/light, number large/bold, description tiny
export default function StatCard({ icon: Icon, label, value, suffix = "", trend }) {
  return (
    <div className="bg-white border-0 rounded-2xl shadow-card p-5">
      <div className="flex items-center justify-between mb-3">
        <p className="text-xs text-slate-400">{label}</p>
        {Icon && (
          <div className="h-7 w-7 rounded-lg bg-brand-secondary/10 flex items-center justify-center">
            <Icon className="h-3.5 w-3.5 text-brand-primary" />
          </div>
        )}
      </div>

      <p className="text-2xl font-bold text-brand-text">
        {value}
        {suffix && <span className="text-sm font-medium text-slate-400">{suffix}</span>}
      </p>

      {trend !== null && trend !== undefined && (
        <p className={`text-xs font-medium mt-1.5 ${trend >= 0 ? "text-emerald-600" : "text-red-500"}`}>
          {trend >= 0 ? "↑" : "↓"} {Math.abs(trend)}% vs bulan lalu
        </p>
      )}
    </div>
  );
}