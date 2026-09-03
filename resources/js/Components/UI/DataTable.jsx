// generic compact table shell — pass `columns` (array of {key, label, align}) and `rows`
// each row can supply a `render` function per cell via columns[].render(row)
export default function DataTable({ columns, rows, emptyMessage = "Tidak ada data", onRowClick }) {
  return (
    <div className="overflow-x-auto -mx-2">
      <table className="w-full text-sm">
        <thead>
          <tr className="border-b border-slate-200">
            {columns.map((col) => (
              <th
                key={col.key}
                className={`px-2 py-2 text-xs font-semibold uppercase tracking-wide text-brand-text/40 ${
                  col.align === "right" ? "text-right" : "text-left"
                }`}
              >
                {col.label}
              </th>
            ))}
          </tr>
        </thead>
        <tbody className="divide-y divide-slate-100">
          {rows.length === 0 ? (
            <tr>
              <td colSpan={columns.length} className="px-2 py-6 text-center text-sm text-brand-text/40">
                {emptyMessage}
              </td>
            </tr>
          ) : (
            rows.map((row) => (
              <tr
                key={row.id}
                onClick={() => onRowClick?.(row)}
                className={`${onRowClick ? "cursor-pointer hover:bg-brand-bg" : ""} transition-colors`}
              >
                {columns.map((col) => (
                  <td
                    key={col.key}
                    className={`px-2 py-2.5 ${col.align === "right" ? "text-right" : "text-left"}`}
                  >
                    {col.render ? col.render(row) : row[col.key]}
                  </td>
                ))}
              </tr>
            ))
          )}
        </tbody>
      </table>
    </div>
  );
}