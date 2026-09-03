import { PieChart, Pie, Cell, ResponsiveContainer, Tooltip } from "recharts";

// colors intentionally match the Badge tone system so the legend reads consistently
const COLORS = {
  pending: "#f59e0b",
  diproses: "#0ea5e9",
  selesai: "#10b981",
};

export default function StatusDonutChart({ data }) {
  const total = data.reduce((sum, item) => sum + item.value, 0);

  return (
    <div className="flex items-center gap-6">
      <div className="relative flex-shrink-0">
        <ResponsiveContainer width={120} height={120}>
          <PieChart>
            <Pie data={data} dataKey="value" nameKey="label" innerRadius={38} outerRadius={56} paddingAngle={2}>
              {data.map((entry) => (
                <Cell key={entry.key} fill={COLORS[entry.key] ?? "#94a3b8"} />
              ))}
            </Pie>
            <Tooltip
              formatter={(value, name) => [`${value} permohonan`, name]}
              contentStyle={{ fontSize: 12, borderRadius: 8, border: "1px solid #e2e8f0" }}
            />
          </PieChart>
        </ResponsiveContainer>
        <div className="absolute inset-0 flex flex-col items-center justify-center">
          <p className="text-lg font-bold text-brand-dark leading-none">{total}</p>
          <p className="text-[10px] text-brand-text/50 mt-0.5">Total</p>
        </div>
      </div>

      <div className="space-y-2 flex-1 min-w-0">
        {data.map((item) => (
          <div key={item.key} className="flex items-center justify-between gap-2 text-sm">
            <div className="flex items-center gap-2 min-w-0">
              <span
                className="h-2 w-2 rounded-full flex-shrink-0"
                style={{ backgroundColor: COLORS[item.key] ?? "#94a3b8" }}
              />
              <span className="text-brand-text/70 truncate">{item.label}</span>
            </div>
            <span className="font-semibold text-brand-dark flex-shrink-0">{item.value}</span>
          </div>
        ))}
      </div>
    </div>
  );
}