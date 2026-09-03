import { Head, Link } from "@inertiajs/react";
import { Users, ClipboardList, Gauge, Activity, ArrowRight, TrendingUp, AlertTriangle } from "lucide-react";
import AdminLayout from "@/Layouts/AdminLayout";
import Card, { CardHeader } from "@/Components/UI/Card";
import StatCard from "@/Components/UI/StatCard";
import TrendAreaChart from "@/Components/Charts/TrendAreaChart";
import AlertList from "@/Components/Dashboard/AlertList";
import ActivityStrip from "@/Components/Dashboard/ActivityStrip";

const STAT_ICONS = {
  pelaku_usaha: Users,
  status_pembinaan: ClipboardList,
  score_mutu: Gauge,
  pembinaan_aktif: Activity,
};

function PetugasDashboard({ stats, alerts, monthlyTrend, recentActivity }) {
  return (
    <div>
      <div className="mb-5">
        <h2 className="text-lg font-bold text-brand-dark">Portfolio Pembinaan</h2>
        <p className="text-xs text-slate-400 mt-0.5">Ringkasan pelaku usaha dan pembinaan di wilayah kerjamu</p>
      </div>

      {/* row 1: 4 stat cards */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        {stats.map((stat) => (
          <StatCard
            key={stat.key}
            icon={STAT_ICONS[stat.key]}
            label={stat.label}
            value={stat.value}
            suffix={stat.suffix}
          />
        ))}
      </div>

      {/* row 2: trend chart (left, wide) + alert list (right, narrow) */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
        <Card className="lg:col-span-2">
          <CardHeader
            title="Grafik Tren Pembinaan"
            description="6 bulan terakhir · permohonan diajukan vs selesai"
            action={<TrendingUp className="h-4 w-4 text-slate-300" />}
          />
          <TrendAreaChart data={monthlyTrend} />
        </Card>

        <Card>
          <CardHeader
            title="Perlu Perhatian"
            description="Permohonan menunggu lama"
            action={<AlertTriangle className="h-4 w-4 text-slate-300" />}
          />
          <AlertList items={alerts} />
        </Card>
      </div>

      {/* row 3: recent activity strip, full width */}
      <Card>
        <CardHeader
          title="Aktivitas Terbaru"
          description="Gabungan permohonan dan pembinaan terbaru"
          action={
            <Link href={route("permohonan.index")} className="focus-ring flex items-center gap-1 text-xs font-medium text-brand-primary hover:text-brand-secondary">
              Lihat semua <ArrowRight className="h-3.5 w-3.5" />
            </Link>
          }
        />
        <ActivityStrip items={recentActivity} />
      </Card>
    </div>
  );
}

function PelakuUsahaDashboard({ stats, recentActivity }) {
  return (
    <div className="max-w-5xl">
      <div className="mb-6">
        <span className="announcement-pill mb-3">Ringkasan Akun</span>
        <h2 className="text-xl font-bold text-brand-dark">Selamat datang kembali</h2>
        <p className="text-xs text-slate-400 mt-1">Pantau status permohonan dan konsultasi teknis yang kamu ajukan.</p>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        {stats.map((stat) => (
          <StatCard key={stat.key} label={stat.label} value={stat.value} suffix={stat.suffix} />
        ))}
      </div>

      <Card>
        <CardHeader
          title="Aktivitas Terbaru"
          description="Permohonan terakhir yang kamu ajukan"
          action={
            <Link href={route("permohonan.index")} className="focus-ring flex items-center gap-1 text-xs font-medium text-brand-primary hover:text-brand-secondary">
              Lihat semua <ArrowRight className="h-3.5 w-3.5" />
            </Link>
          }
        />

        <div className="divide-y divide-slate-50">
          {recentActivity.map((item) => (
            <Link
              key={item.id}
              href={route("permohonan.show", item.id)}
              className="focus-ring flex items-center justify-between gap-4 py-3 hover:bg-brand-bg -mx-2 px-2 rounded-xl transition-colors"
            >
              <div className="min-w-0">
                <p className="text-sm font-medium text-brand-dark truncate">{item.title}</p>
                <p className="text-xs text-slate-400 mt-0.5">{item.subtitle} · {item.date}</p>
              </div>
            </Link>
          ))}
        </div>
      </Card>
    </div>
  );
}

export default function Dashboard(props) {
  return (
    <AdminLayout title="Dashboard">
      <Head title="Dashboard" />
      {props.isPelakuUsaha ? <PelakuUsahaDashboard {...props} /> : <PetugasDashboard {...props} />}
    </AdminLayout>
  );
}