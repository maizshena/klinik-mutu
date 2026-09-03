const TONES = {
  primary: "bg-brand-primary",
  emerald: "bg-emerald-500",
  amber: "bg-amber-500",
  sky: "bg-sky-500",
};

export default function ProgressBar({ value, max = 100, tone = "primary" }) {
  const percent = Math.min(100, Math.round((value / max) * 100));

  return (
    <div className="h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
      <div className={`h-full rounded-full ${TONES[tone]} transition-all`} style={{ width: `${percent}%` }} />
    </div>
  );
}