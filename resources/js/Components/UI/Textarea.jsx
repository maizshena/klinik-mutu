import { forwardRef } from "react";
import { AlertCircle } from "lucide-react";

const Textarea = forwardRef(function Textarea({ error, className = "", rows = 4, ...props }, ref) {
  return (
    <div>
      <textarea
        ref={ref}
        rows={rows}
        className={`focus-ring w-full rounded-xl border text-sm text-brand-text shadow-xs
          placeholder:text-brand-text/40 px-3.5 py-2.5 transition-colors bg-white
          ${error ? "border-red-300" : "border-slate-200 focus-visible:border-brand-secondary"}
          ${className}`}
        {...props}
      />

      {error && (
        <p className="mt-1.5 flex items-center gap-1 text-xs font-medium text-red-600">
          <AlertCircle className="h-3.5 w-3.5" />
          {error}
        </p>
      )}
    </div>
  );
});

export default Textarea;