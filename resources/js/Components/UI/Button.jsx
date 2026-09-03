import { forwardRef } from "react";
import { Loader2 } from "lucide-react";

// variant styles now reference brand tokens only — no raw hex, no generic tailwind blues
const VARIANTS = {
  primary: "bg-brand-primary text-white hover:bg-brand-secondary border border-transparent",
  secondary: "bg-white text-brand-text hover:bg-brand-bg border border-slate-200",
  ghost: "bg-transparent text-brand-text hover:bg-brand-bg border border-transparent",
  danger: "bg-red-600 text-white hover:bg-red-700 border border-transparent",
};

const SIZES = {
  sm: "px-3 py-1.5 text-sm",
  md: "px-4 py-2 text-sm",
  lg: "px-5 py-2.5 text-base",
};

const Button = forwardRef(function Button(
  { variant = "primary", size = "md", icon: Icon, loading = false, disabled, className = "", children, ...props },
  ref
) {
  return (
    <button
      ref={ref}
      disabled={disabled || loading}
      className={`focus-ring inline-flex items-center justify-center gap-2 rounded-xl font-semibold
        shadow-xs transition-colors disabled:cursor-not-allowed disabled:opacity-60
        ${VARIANTS[variant]} ${SIZES[size]} ${className}`}
      {...props}
    >
      {loading ? <Loader2 className="h-4 w-4 animate-spin" /> : Icon ? <Icon className="h-4 w-4" /> : null}
      {children}
    </button>
  );
});

export default Button;