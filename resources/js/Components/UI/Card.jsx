// borderless card: separation comes purely from white-on-off-white contrast + soft shadow
export default function Card({ className = "", padding = "p-5", children, ...props }) {
  return (
    <div
      className={`bg-white border-0 rounded-2xl shadow-card ${padding} ${className}`}
      {...props}
    >
      {children}
    </div>
  );
}

export function CardHeader({ title, description, action, className = "" }) {
  return (
    <div className={`flex items-start justify-between gap-4 mb-4 ${className}`}>
      <div>
        <h3 className="text-sm font-semibold text-brand-dark">{title}</h3>
        {description && <p className="text-xs text-slate-400 mt-0.5">{description}</p>}
      </div>
      {action}
    </div>
  );
}