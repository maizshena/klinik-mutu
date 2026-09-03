import { AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from "recharts";

// custom tooltip styled with brand tokens instead of recharts' default dark box
function CustomTooltip({ active, payload, label }) {
  if (!active || !payload?.length) return null;

  return (
    <div className="rounded-xl border border-slate-200 bg-white px-3 py-2 shadow-card text-xs">
      <p className="font-semibold text-brand-dark mb-1">{label}</p>
      {payload.map((entry) => (
        <p key={entry.dataKey} style={{ color: entry.color }} className="font-medium">
          {entry.name}: {entry.value}
        </p>
      ))}
    </div>
  );
}

export default function TrendAreaChart({ data }) {
  return (
    <ResponsiveContainer width="100%" height={240}>
      <AreaChart data={data} margin={{ top: 8, right: 8, left: -16, bottom: 0 }}>
        <defs>
          <linearGradient id="submittedGradient" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stopColor="#2F6690" stopOpacity={0.25} />
            <stop offset="100%" stopColor="#2F6690" stopOpacity={0} />
          </linearGradient>
          <linearGradient id="completedGradient" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stopColor="#10b981" stopOpacity={0.25} />
            <stop offset="100%" stopColor="#10b981" stopOpacity={0} />
          </linearGradient>
        </defs>

        <CartesianGrid strokeDasharray="3 3" stroke="#e2e8f0" vertical={false} />
        <XAxis dataKey="label" tick={{ fontSize: 12, fill: "#64748b" }} axisLine={false} tickLine={false} />
        <YAxis tick={{ fontSize: 12, fill: "#64748b" }} axisLine={false} tickLine={false} width={32} />
        <Tooltip content={<CustomTooltip />} />

        <Area
          type="monotone"
          dataKey="submitted"
          name="Diajukan"
          stroke="#2F6690"
          strokeWidth={2}
          fill="url(#submittedGradient)"
        />
        <Area
          type="monotone"
          dataKey="completed"
          name="Selesai"
          stroke="#10b981"
          strokeWidth={2}
          fill="url(#completedGradient)"
        />
      </AreaChart>
    </ResponsiveContainer>
  );
}