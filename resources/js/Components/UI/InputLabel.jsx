export default function InputLabel({ value, required = false, className = "", ...props }) {
  return (
    <label {...props} className={`block text-sm font-medium text-brand-text mb-1.5 ${className}`}>
      {value}
      {required && <span className="text-red-500 ml-0.5">*</span>}
    </label>
  );
}