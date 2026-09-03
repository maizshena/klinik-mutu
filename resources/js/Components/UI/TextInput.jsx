import { forwardRef } from "react";
import { AlertCircle } from "lucide-react";

const TextInput = forwardRef(function TextInput(
  { icon: Icon, error, className = "", isFocused = false, ...props },
  ref
) {
  return (
    <div>
      <div className="relative">
        {Icon && <Icon className="h-4 w-4 text-brand-text/40 absolute left-3 top-1/2 -translate-y-1/2" />}

        <input
          ref={ref}
          autoFocus={isFocused}
          className={`focus-ring w-full rounded-xl border text-sm text-brand-text shadow-xs
            placeholder:text-brand-text/40 py-2.5 transition-colors bg-white
            ${Icon ? "pl-9 pr-3" : "px-3.5"}
            ${error ? "border-red-300" : "border-slate-200 focus-visible:border-brand-secondary"}
            ${className}`}
          {...props}
        />
      </div>

      {error && (
        <p className="mt-1.5 flex items-center gap-1 text-xs font-medium text-red-600">
          <AlertCircle className="h-3.5 w-3.5" />
          {error}
        </p>
      )}
    </div>
  );
});

export default TextInput;